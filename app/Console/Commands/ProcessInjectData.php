<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InjectData;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Instansi;
use App\Models\PendaftaranPelatihan;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use App\Models\Tes;
use App\Models\CabangDinas;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ProcessInjectData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:inject-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from inject_data tables to main application tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration from inject_data...');

        $items = InjectData::with('details')->cursor();

        $count = 0;

        DB::beginTransaction();
        try {
            // Ensure Role 'peserta' exists
            Role::firstOrCreate(['name' => 'peserta', 'guard_name' => 'web']);

            foreach ($items as $data) {
                // Ensure CabangDinas exists (dummy)
                $cabangDinas = CabangDinas::firstOrCreate(
                    ['nama' => 'Cabang Dinas Umum'],
                    ['alamat' => '-', 'laman' => '-']
                );

                // 1. Instansi
                // Logic: Find by exact name OR create new
                $instansiName = $data->instansi;
                if (empty($instansiName) || $instansiName === 'null') {
                    $instansiName = 'Instansi Umum';
                }

                $instansi = Instansi::firstOrCreate(
                    ['asal_instansi' => $instansiName],
                    [
                        'kota' => ' -', // Default value
                        'email' => null,
                        'alamat_instansi' => '-',
                        'kota_id' => '-',
                        'kompetensi_keahlian' => '-',
                        'cabangDinas_id' => $cabangDinas->id,
                    ]
                );

                // 2. User
                // Logic: Find by email. If email is 'null' or empty, generate a dummy email based on unique_key or name
                $email = $data->email;
                if (empty($email) || $email === 'null') {
                    $slugName = Str::slug($data->nama ?? 'user');
                    // Prevent empty slug
                    if (empty($slugName)) $slugName = 'user';
                    $email = "{$slugName}.{$data->unique_key}@example.com";
                }

                $userName = $data->nama;
                if (empty($userName) || $userName === 'null') {
                    $userName = 'User ' . $data->unique_key;
                }

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $userName,
                        'password' => Hash::make('password'), // Default password
                    ]
                );

                // Assign role 'peserta' if not exists (assuming spatie/laravel-permission)
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('peserta');
                }

                // 3. Peserta
                $pelatihanId = $data->pelatihan_id;
                if (empty($pelatihanId) || $pelatihanId === 'null') {
                    // Fallback pelatihan ID? Better to skip or use a default if exists.
                    // Assuming ID 1 exists or strictly from data. 
                    // Let's assume we proceed if we can, or critical failure.
                    // For now, let's keep it as is, usually key FK must exist.
                    // But if strictly asking for dummy... let's assume 1.
                    // $pelatihanId = 1; 
                }

                $pesertaNama = $data->nama;
                if (empty($pesertaNama) || $pesertaNama === 'null') {
                    $pesertaNama = $user->name;
                }

                $peserta = Peserta::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'pelatihan_id' => $data->pelatihan_id,
                    ],
                    [
                        'instansi_id' => $instansi->id,
                        'nama' => $pesertaNama,
                        // Fill other fields with defaults if null
                        'nik' => $data->unique_key,
                        'jenis_kelamin' => 'Laki-laki', // Default matching ENUM
                        'tanggal_lahir' => now(), // Default
                        'tempat_lahir' => '-',
                        'alamat' => '-',
                        'no_hp' => '-',
                        'agama' => 'Islam', // most common default
                    ]
                );

                // 4. PendaftaranPelatihan
                $kompetensiId = $data->kompetensi_id;
                // if empty, maybe null is allowed or use default?
                // Migration says foreign key... so skipping fallback to avoid invalid FK error unless known.

                $pendaftaran = PendaftaranPelatihan::updateOrCreate(
                    [
                        'peserta_id' => $peserta->id,
                        'pelatihan_id' => $data->pelatihan_id,
                    ],
                    [
                        'kompetensi_id' => $data->kompetensi_id,
                        'status' => 'Lulus', // valid ENUM
                        'status_pendaftaran' => 'diterima',
                        'tanggal_pendaftaran' => now(),
                        'nilai_pre_test' => $this->parseScore($data->nilai_pre_test),
                        'nilai_post_test' => $this->parseScore($data->nilai_post_test),
                        'nomor_registrasi' => 'REG-' . $data->unique_key,
                        'kelas' => 'A',
                    ]
                );

                // 5. Percobaan (Test Attempt)
                // Need a test ID. inject_data has test_id.
                if ($data->tes_id && $data->tes_id !== 'null') {
                    $percobaan = Percobaan::firstOrCreate(
                        [
                            'peserta_id' => $peserta->id,
                            'tes_id' => $data->tes_id,
                        ],
                        [
                            'pelatihan_id' => $data->pelatihan_id,
                            'waktu_mulai' => now(),
                            'waktu_selesai' => now(),
                            'skor' => $this->parseScore($data->nilai_post_test),
                            'lulus' => true, // Assume passed
                            'tipe' => 'post-test', // Default guess
                        ]
                    );

                    // 6. JawabanUser (Inject Data Details)
                    foreach ($data->details as $detail) {
                        // Validate Pertanyaan ID
                        if (!Pertanyaan::where('id', $detail->attribute)->exists()) {
                            continue; // Skip if question not found
                        }

                        // Check if value is numeric, might be opsi_jawaban_id
                        $opsiId = null;
                        $jawabanTeks = $detail->value;
                        if ($jawabanTeks === 'null') $jawabanTeks = '-';

                        // Simple heuristic: if value is numeric and smallish, it might be ID. 
                        // But value is longText, could be anything.
                        // However, usually CBT answers are opsi IDs.
                        if (is_numeric($detail->value)) {
                            // Validate Opsi existence
                            if (OpsiJawaban::where('id', $detail->value)->exists()) {
                                $opsiId = $detail->value;
                                $jawabanTeks = null;
                            } else {
                                // If numeric but not found as ID, treat as text
                                $jawabanTeks = $detail->value;
                            }
                        }

                        JawabanUser::updateOrCreate(
                            [
                                'percobaan_id' => $percobaan->id,
                                'pertanyaan_id' => $detail->attribute, // Mapping attribute to pertanyaan_id
                            ],
                            [
                                'opsi_jawaban_id' => $opsiId,
                                'jawaban_teks' => $jawabanTeks,
                                'nilai_jawaban' => 0, // Calculate later if needed
                            ]
                        );
                    }
                }

                $count++;
                if ($count % 100 === 0) {
                    $this->info("Processed {$count} records...");
                }
            }

            DB::commit();
            $this->info("Migration completed! Processed {$count} records.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error occurred: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    private function parseScore($score)
    {
        if (empty($score)) return 0;
        // Handle format like "80 / 100" or just "80"
        $parts = explode('/', $score);
        return (float) trim($parts[0]);
    }
}
