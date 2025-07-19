<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On; // FIXO: Adicionada a importação para o atributo #[On]

new class extends Component {
    public string $search = '';
    public string $role = '';
    public string $status = '';

    /**
     * Ouve um evento para aplicar um filtro rápido vindo de outro componente.
     */
    #[On('apply-quick-filter')]
    public function applyQuickFilter($filter)
    {
        if (isset($filter['clear']) && $filter['clear']) {
            $this->clearFilters();
            return;
        }

        if (isset($filter['status'])) {
            $this->status = $filter['status'];
        }

        if (isset($filter['role'])) {
            $this->role = $filter['role'];
        }

        // Dispara o evento para notificar a tabela que os filtros foram atualizados.
        $this->dispatchFilters();
    }

    /**
     * Hook do Livewire que é executado automaticamente sempre que uma propriedade
     * (search, role, status) é atualizada pela interface.
     */
    public function updated($property): void
    {
        $this->dispatchFilters();
    }

    /**
     * Limpa todos os filtros. O hook 'updated' será chamado para cada propriedade,
     * notificando a tabela automaticamente.
     */
    public function clearFilters(): void
    {
        $this->reset(['search', 'role', 'status']);
    }

    /**
     * Método auxiliar para evitar duplicação de código.
     */
    private function dispatchFilters(): void
    {
        $this->dispatch('filters-updated', [
            'search' => $this->search,
            'role' => $this->role,
            'status' => $this->status,
        ]);
    }
}; ?>

<div class="card-bg bg-white dark:bg-zinc-800 rounded-lg shadow-sm border dark:border-zinc-700 p-6 mb-6" x-data="{
        showFilters: window.innerWidth >= 1024, // Mostra filtros por padrão em telas grandes
        get hasActiveFilters() {
            return $wire.search || $wire.role || $wire.status
        }
    }">
    <!-- Header dos Filtros -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Pesquisa e Filtros
        </h3>

        <div class="flex items-center gap-2">
            <!-- Indicador de filtros ativos -->
            <div x-show="hasActiveFilters" x-transition class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full"
                style="display: none;">
                Filtros ativos
            </div>

            <!-- Botão toggle para mostrar/esconder filtros em telas menores -->
            <button @click="showFilters = !showFilters"
                class="lg:hidden p-2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!showFilters" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7"></path>
                    <path x-show="showFilters" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 15l7-7 7 7" style="display: none;"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Conteúdo dos Filtros -->
    <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:block" style="display: none;">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Barra de Pesquisa -->
            <div class="flex-1 lg:max-w-md">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Pesquisar usuários..."
                    icon="magnifying-glass" type="text" />
            </div>

            <!-- Filtros Dropdown -->
            <div class="flex flex-col sm:flex-row gap-4 lg:ml-auto">
                <div class="min-w-48">
                    <flux:select wire:model.live="role" placeholder="Todas as funções">
                        <flux:select.option value="">Todas as funções</flux:select.option>
                        <flux:select.option value="admin">Administrador</flux:select.option>
                        <flux:select.option value="user">Usuário</flux:select.option>
                    </flux:select>
                </div>
                <div class="min-w-48">
                    <flux:select wire:model.live="status" placeholder="Todos os status">
                        <flux:select.option value="">Todos os status</flux:select.option>
                        <flux:select.option value="active">Ativo</flux:select.option>
                        <flux:select.option value="inactive">Inativo</flux:select.option>
                        <flux:select.option value="blocked">Bloqueado</flux:select.option>
                    </flux:select>
                </div>
                <flux:button wire:click="clearFilters" variant="ghost" icon="x-mark" class="shrink-0">
                    Limpar
                </flux:button>
            </div>
        </div>

        <!-- Filtros Rápidos -->
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-600">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Filtros rápidos:</p>
            <div class="flex flex-wrap gap-2">
                <button @click="$wire.set('role', 'admin')" class="px-3 py-1 text-xs rounded-full transition-colors"
                    :class="{ 
                        'bg-red-500 dark:bg-red-600 text-white ring-2 ring-red-300 dark:ring-red-400': $wire.role === 'admin', 
                        'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800': $wire.role !== 'admin' 
                    }">
                    👑 Administradores
                </button>
                <button @click="$wire.set('status', 'active')" class="px-3 py-1 text-xs rounded-full transition-colors"
                    :class="{ 
                        'bg-green-500 dark:bg-green-600 text-white ring-2 ring-green-300 dark:ring-green-400': $wire.status === 'active', 
                        'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800': $wire.status !== 'active' 
                    }">
                    ✅ Usuários Ativos
                </button>
                <button @click="$wire.set('status', 'inactive')"
                    class="px-3 py-1 text-xs rounded-full transition-colors"
                    :class="{ 
                        'bg-yellow-500 dark:bg-yellow-600 text-white ring-2 ring-yellow-300 dark:ring-yellow-400': $wire.status === 'inactive', 
                        'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800': $wire.status !== 'inactive' 
                    }">
                    ⏳ Pendentes
                </button>
            </div>
        </div>
    </div>
</div>