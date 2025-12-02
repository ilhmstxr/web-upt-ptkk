<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\InstansiResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\InstansiResource;
use App\Models\Instansi;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ListInstansis extends ListRecords
{
    protected static string $resource = InstansiResource::class;

    #[Url]
    public $search = '';

    public $newInstansi = [
        'id' => null,
        'name' => '',
        'address' => '',
        'email' => '',
        'phone' => '',
    ];

    public $isEditing = false;

    public function getView(): string
    {
        return 'filament.clusters.kesiswaan.resources.instansi-resource.pages.list-instansi';
    }

    public function getInstansisProperty()
    {
        return Instansi::query()
            ->when($this->search, function (Builder $query) {
                $query->where('asal_instansi', 'like', '%' . $this->search . '%')
                      ->orWhere('alamat_instansi', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(5);
    }

    public function getStatsProperty()
    {
        $baseQuery = Instansi::query();
        
        return [
            'total' => $baseQuery->count(),
            // Removed status-based stats as the column is removed
            'total_peserta' => \App\Models\Peserta::count(), 
        ];
    }

    public function resetFilters()
    {
        $this->reset(['search']);
    }

    public function editInstansi($id)
    {
        $instansi = Instansi::find($id);
        if ($instansi) {
            $this->newInstansi = [
                'id' => $instansi->id,
                'name' => $instansi->asal_instansi,
                'address' => $instansi->alamat_instansi,
                'email' => $instansi->email,
                'phone' => $instansi->no_telepon,
            ];
            $this->isEditing = true;
            $this->dispatch('open-modal', id: 'modalAddInstansi');
        }
    }

    public function deleteInstansi($id)
    {
        $instansi = Instansi::find($id);
        if ($instansi) {
            $name = $instansi->asal_instansi;
            $instansi->delete();
            
            Notification::make()
                ->title('Instansi Dihapus')
                ->body("Instansi $name telah dihapus.")
                ->success()
                ->send();
        }
    }

    public function saveInstansi()
    {
        $this->validate([
            'newInstansi.name' => 'required',
            'newInstansi.address' => 'required',
        ]);

        if ($this->isEditing && $this->newInstansi['id']) {
            $instansi = Instansi::find($this->newInstansi['id']);
            $instansi->update([
                'asal_instansi' => $this->newInstansi['name'],
                'alamat_instansi' => $this->newInstansi['address'],
                'email' => $this->newInstansi['email'],
                'no_telepon' => $this->newInstansi['phone'],
            ]);
            
            Notification::make()->title('Data Diperbarui')->success()->send();
        } else {
            Instansi::create([
                'asal_instansi' => $this->newInstansi['name'],
                'alamat_instansi' => $this->newInstansi['address'],
                'email' => $this->newInstansi['email'],
                'no_telepon' => $this->newInstansi['phone'],
                'kota_id' => '3204', // Default mock
                'kota' => 'Bandung', // Default mock
                'bidang_keahlian' => 'IT', // Default mock
                'kelas' => 'A', // Default mock
                'cabangDinas_id' => 1, // Default mock
            ]);
            
            Notification::make()->title('Instansi Ditambahkan')->success()->send();
        }

        $this->reset('newInstansi', 'isEditing');
        $this->dispatch('close-modal', id: 'modalAddInstansi');
    }

    public function openModal()
    {
        $this->reset('newInstansi', 'isEditing');
        $this->dispatch('open-modal', id: 'modalAddInstansi');
    }
}
