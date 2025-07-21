<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public array $roles = [];

    public function mount()
    {
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::withCount(['permissions', 'users'])
            ->get()
            ->toArray();
    }

    public function editRole($id)
    {
        session()->flash('message', 'Edit role with ID: ' . $id);
    }

    public function deleteRole($id)
    {
        Role::find($id)->delete();
        $this->loadRoles();
        session()->flash('message', __('roles.deleted_successfully'));
    }
}; ?>

<div class="grid gap-4 md:grid-cols-3">
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Painel', 'url' => route('panel.roles.index')],
        ['label' => __('roles.title'), 'active' => true],
    ]" title="{{ __('roles.title') }}" />

    <livewire:panel.components.search-filter placeholder="{{ __('roles.search_placeholder') }}" />

    <div class="card col-span-12">
        <div class="grid grid-cols-12 gap-4 p-4">
            @foreach ($roles as $role)
                <div class="card col-span-4">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <h3 class="text-lg font-semibold">{{ $role['name'] }}</h3>
                            <div class="flex gap-2">
                                <livewire:components.button wire:click="editRole({{ $role['id'] }})" :text="__('roles.edit')"
                                    color="secondary" class="btn-sm" />
                                <livewire:components.button wire:click="deleteRole({{ $role['id'] }})"
                                    wire:confirm="{{ __('roles.confirm_delete') }}" :text="__('roles.delete')" color="danger"
                                    class="btn-sm" />
                            </div>
                        </div>
                        @if (isset($role['description']))
                            <p class="text-gray-600 text-sm mb-2">{{ $role['description'] }}</p>
                        @endif
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>{{ __('roles.permissions') }}: {{ $role['permissions_count'] ?? 0 }}</span>
                            <span>{{ __('roles.users_count') }}: {{ $role['users_count'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
