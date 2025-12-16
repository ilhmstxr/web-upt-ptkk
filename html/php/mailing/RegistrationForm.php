<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RegistrationSuccess;

class RegistrationForm extends Component
{
    use WithFileUploads;

    public $step = 1;

    // Step 1: Biodata Diri
    public $name, $nik, $birth_place, $birth_date, $gender, $religion, $address, $phone, $email;

    // Step 2: Biodata Sekolah
    public $school_name, $school_address, $competence, $class, $dinas_branch;

    // Step 3: Lampiran
    public $ktp_path, $ijazah_path, $surat_tugas_path, $surat_sehat_path, $pas_foto_path, $surat_tugas_nomor;

    /**
     * Validasi dinamis berdasarkan step.
     */
    protected function rules()
    {
        return match ($this->step) {
            1 => [
                'name' => 'required|string|max:255',
                'nik' => 'required|numeric|digits:16|unique:registrations,nik',
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'religion' => 'required|string|max:50',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'required|email|max:255|unique:registrations,email',
            ],
            2 => [
                'school_name' => 'required|string|max:255',
                'school_address' => 'required|string|max:255',
                'competence' => 'required|string|max:255',
                'class' => 'required|string|max:50',
                'dinas_branch' => 'required|string|max:100',
            ],
            3 => [
                'ktp_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'ijazah_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'surat_tugas_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'surat_tugas_nomor' => 'required|string|max:255',
                'surat_sehat_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'pas_foto_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ],
            default => [],
        };
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function nextStep()
    {
        $this->validate($this->rules());
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate($this->rules());

        try {
            // Upload files
            $ktpPath = $this->ktp_path->store('documents', 'public');
            $ijazahPath = $this->ijazah_path->store('documents', 'public');
            $suratTugasPath = $this->surat_tugas_path->store('documents', 'public');
            $suratSehatPath = $this->surat_sehat_path->store('documents', 'public');
            $pasFotoPath = $this->pas_foto_path->store('documents', 'public');

            // Simpan ke database
            $registration = Registration::create([
                'name' => $this->name,
                'nik' => $this->nik,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
                'religion' => $this->religion,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'school_name' => $this->school_name,
                'school_address' => $this->school_address,
                'competence' => $this->competence,
                'class' => $this->class,
                'dinas_branch' => $this->dinas_branch,
                'surat_tugas_nomor' => $this->surat_tugas_nomor,
                'ktp_path' => $ktpPath,
                'ijazah_path' => $ijazahPath,
                'surat_tugas_path' => $suratTugasPath,
                'surat_sehat_path' => $suratSehatPath,
                'pas_foto_path' => $pasFotoPath,
            ]);

            // Kirim email konfirmasi
            Mail::to($registration->email)->send(new RegistrationSuccess($registration));

            session()->flash('message', 'Pendaftaran berhasil! Silakan cek email Anda.');
            return redirect()->route('pendaftaran.sukses', ['registration_id' => $registration->id]);

        } catch (\Exception $e) {
            Log::error('Kesalahan saat pendaftaran: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.registration-form');
    }
}
