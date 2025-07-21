<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;

new class extends Component {
    public array $permissions = [];

    public function mount()
    {
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $this->permissions = Permission::all()->toArray();
    }

    public function editPermission($id)
    {
        session()->flash('message', 'Edit permission with ID: ' . $id);
    }

    public function deletePermission($id)
    {
        Permission::find($id)->delete();
        $this->loadPermissions();
        session()->flash('message', 'Permission deleted successfully');
    }
}; ?>

<div class="grid gap-4 md:grid-cols-3">
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Painel', 'url' => route('panel.permissions.index')],
        ['label' => 'Permissões', 'active' => true],
    ]" title="Listagem" />

    <livewire:panel.components.search-filter />

    <div class="card col-span-12">
        <div class="grid grid-cols-12 gap-4 p-4">
            @foreach ($permissions as $permission)
                <div class="card col-span-4">
                    <div class="p-4">
                        <h3 class="text-lg text-center font-semibold">{{ $permission['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
