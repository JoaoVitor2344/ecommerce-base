<?php

use Livewire\Volt\Component;

new class extends Component {
    // A propriedade `$showWelcome` e o método `dismissWelcome` foram removidos.
    // O estado do card de boas-vindas agora é controlado 100% pelo Alpine.js.

    /**
     * Ação para a página de criação de usuário.
     */
    public function createUser()
    {
        session()->flash('message', 'Funcionalidade de criar usuário em desenvolvimento.');

        // Certifique-se de que esta rota existe em seus arquivos de rota.
        return redirect()->route('panel.users.create');
    }
}; ?>

<x-layouts.app :title="__('users.Users')">
    {{-- O x-data agora inicializa o `showWelcome` com base no localStorage do navegador --}}
    <div class="p-6 max-w-7xl mx-auto col-span-12" x-data="{
        loading: false,
        showWelcome: !localStorage.getItem('welcomeDismissed')
    }" x-init="
        document.addEventListener('livewire:navigating', () => loading = true);
        document.addEventListener('livewire:navigated', () => loading = false);
    ">
        <!-- Loading Overlay (Alpine.js) -->
        <div x-show="loading" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center"
            style="display: none;">
            <div class="bg-white rounded-lg p-6 shadow-xl">
                <div class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-gray-700">Carregando...</span>
                </div>
            </div>
        </div>

        <!-- Welcome Card (Controlado por Alpine.js) -->
        <div x-show="showWelcome" class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 mb-6 text-white"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95" style="display: none;">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-semibold mb-2">
                        🎉 Bem-vindo ao Gerenciamento de Usuários!
                    </h2>
                    <p class="text-blue-100 mb-4">
                        Gerencie usuários, defina permissões e monitore atividades do sistema.
                    </p>
                    <div class="flex gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">Livewire</span>
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">Volt</span>
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">Alpine.js</span>
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">Tailwind</span>
                    </div>
                </div>
                {{-- O botão agora manipula o Alpine e o localStorage, sem chamar o backend ($wire) --}}
                <button @click="showWelcome = false; localStorage.setItem('welcomeDismissed', 'true')"
                    class="text-white hover:text-gray-200 transition-colors flex-shrink-0 ml-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <flux:heading size="xl" level="1">Usuários</flux:heading>
            <flux:subheading size="lg" class="mt-2">
                Gerencie usuários do sistema e suas permissões
            </flux:subheading>
        </div>

        <!-- Cards de Estatísticas -->
        <livewire:panel.users.components.stats-cards />

        <!-- Barra de Pesquisa e Filtros -->
        <livewire:panel.users.components.search-filters />

        <!-- Tabela de Usuários -->
        <livewire:panel.users.components.users-table />

        <!-- Floating Action Button (Mobile) -->
        <div class="fixed bottom-6 right-6 lg:hidden">
            <button wire:click="createUser"
                class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </button>
        </div>
    </div>
</x-layouts.app>