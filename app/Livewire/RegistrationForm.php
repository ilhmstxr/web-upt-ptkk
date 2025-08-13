<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Registration;
use Livewire\WithFileUploads;

class RegistrationForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // Data Biodata Diri
    public $name, $nik, $birth_place, $birth_date, $gender, $religion, $address, $phone, $email;

    // Data Biodata Sekolah
    public $school_name, $school_address, $competence, $class, $dinas_branch;

    // Data Lampiran
    public $ktp_path, $ijazah_path, $surat_tugas_path, $surat_sehat_path, $pas_foto_path, $surat_tugas_nomor;

    // Validasi untuk setiap langkah
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function rules()
    {
        if ($this->currentStep == 1) {
            return [
                'name' => 'required',
                'nik' => 'required|numeric|digits:16',
                // tambahkan validasi lainnya
            ];
        } elseif ($this->currentStep == 2) {
            return [
                'school_name' => 'required',
                'school_address' => 'required',
                // tambahkan validasi lainnya
            ];
        } elseif ($this->currentStep == 3) {
            return [
                'ktp_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'ijazah_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                // tambahkan validasi lainnya
            ];
        }
    }

    public function nextStep()
    {
        $this->validate($this->rules());
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validate($this->rules());

        // Simpan file
        $ktpPath = $this->ktp_path->store('documents', 'public');
        $ijazahPath = $this->ijazah_path->store('documents', 'public');
        // Simpan file lainnya...

        // Buat entri baru di database
        Registration::create([
            'name' => $this->name,
            'nik' => $this->nik,
            // ... field lainnya
            'ktp_path' => $ktpPath,
            'ijazah_path' => $ijazahPath,
            // ... path file lainnya
        ]);

        session()->flash('message', 'Pendaftaran berhasil!');
        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.registration-form');
    }
}