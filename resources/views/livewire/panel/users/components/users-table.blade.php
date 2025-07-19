<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $role = '';
    public string $status = '';

    #[On('filters-updated')]
    public function updateFilters($filters)
    {
        $this->search = $filters['search'];
        $this->role = $filters['role'];
        $this->status = $filters['status'];
        $this->resetPage();
    }

    public function with(): array
    {
        $query = User::query()->with('roles')->orderBy('created_at', 'desc');

        // Aplicar filtro de pesquisa
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Aplicar filtro de role
        if ($this->role) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->role);
            });
        }

        // Aplicar filtro de status (exemplo baseado em email_verified_at)
        if ($this->status === 'active') {
            $query->whereNotNull('email_verified_at');
        } elseif ($this->status === 'inactive') {
            $query->whereNull('email_verified_at');
        }

        return [
            'users' => $query->paginate(10),
        ];
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            session()->flash('message', 'Usuário excluído com sucesso!');
            $this->dispatch('user-deleted');
        }
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            if ($user->email_verified_at) {
                $user->email_verified_at = null;
                $message = 'Usuário desativado com sucesso!';
            } else {
                $user->email_verified_at = now();
                $message = 'Usuário ativado com sucesso!';
            }
            $user->save();
            session()->flash('message', $message);
            $this->dispatch('user-updated');
        }
    }
}; ?>

<div class="card-bg overflow-hidden" x-data="{
    selectedUsers: [],
    selectAll: false,
    showBulkActions: false
}" x-init="$watch('selectedUsers', value => {
    showBulkActions = value.length > 0;
    selectAll = value.length === {{ $users->count() }};
})">
    @if (session()->has('message'))
        <div class="px-6 py-4 bg-green-50 dark:bg-green-900/50 border-b border-green-200 dark:border-green-800" x-data="{ show: true }" x-show="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <flux:icon.check-circle class="h-5 w-5 text-green-600 dark:text-green-400 mr-2" />
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('message') }}</p>
                </div>
                <button @click="show = false"
                    class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Header da Tabela com Ações em Lote -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-transparent dark:bg-zinc-800 text-gray-900 dark:text-white">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Usuários ({{ $users->total() }})
                </h3>

                <!-- Ações em Lote (Alpine.js) -->
                <div x-show="showBulkActions" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        <span x-text="selectedUsers.length"></span> selecionado(s)
                    </span>
                    <button
                        class="px-3 py-1 text-xs bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition-colors"
                        @click="if(confirm('Excluir usuários selecionados?')) { /* Ação de exclusão em lote */ }">
                        Excluir Selecionados
                    </button>
                </div>
            </div>

            <flux:button variant="primary" icon="plus" href="{{ route('panel.users.create') }}" wire:navigate>
                Novo Usuário
            </flux:button>
        </div>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
            <thead class="bg-gray-50 dark:bg-zinc-900">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Usuário
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Função
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Status
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Criado em
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <flux:avatar size="sm" :initials="$user->initials()" />
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($user->roles->isNotEmpty())
                                @foreach ($user->roles as $role)
                                    <flux:badge size="sm" :color="$role->name === 'admin' ? 'red' : 'blue'" class="mr-1">
                                        {{ ucfirst($role->name) }}
                                    </flux:badge>
                                @endforeach
                            @else
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Sem Função</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($user->email_verified_at)
                                <flux:badge size="sm" color="green">Ativo</flux:badge>
                            @else
                                <flux:badge size="sm" color="yellow">Pendente</flux:badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button size="sm" variant="ghost" icon="ellipsis-horizontal" />

                                <flux:menu>
                                    <flux:menu.item icon="eye" href="{{ route('panel.users.show', $user) }}" wire:navigate>
                                        Visualizar
                                    </flux:menu.item>

                                    <flux:menu.item icon="pencil" href="{{ route('panel.users.edit', $user) }}"
                                        wire:navigate>
                                        Editar
                                    </flux:menu.item>

                                    @if ($user->id !== auth()->id())
                                        <flux:menu.separator />

                                        <flux:menu.item icon="{{ $user->email_verified_at ? 'no-symbol' : 'check-circle' }}"
                                            wire:click="toggleUserStatus({{ $user->id }})">
                                            {{ $user->email_verified_at ? 'Desativar' : 'Ativar' }}
                                        </flux:menu.item>

                                        <flux:menu.item icon="trash" variant="danger" wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Tem certeza que deseja excluir este usuário?">
                                            Excluir
                                        </flux:menu.item>
                                    @endif
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <flux:icon.users class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" />
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">Nenhum usuário
                                    encontrado</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tente ajustar seus critérios de pesquisa ou filtro</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if ($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
            {{ $users->links() }}
        </div>
    @endif
</div>