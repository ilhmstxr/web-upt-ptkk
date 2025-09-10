<div>
    <input type="text" wire:model="search" placeholder="Cari user...">

    <table>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Roles</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
        </tr>
        @endforeach
    </table>
</div>
