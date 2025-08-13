<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class UserList extends Component
{
    public $search = '';

    public function render()
    {
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();

        return view('livewire.user-list', compact('users'));
    }
}
