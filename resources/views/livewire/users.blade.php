<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Computed;
use Spatie\Permission\Models\Role;

new class extends Component {
    use WithPagination;

    #[Computed]
    public function users()
    {
        return User::with('roles')->latest()->paginate(10);
    }

    public function delete(User $user)
    {
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Você não pode deletar sua própria conta.');
            return;
        }

        $user->delete();
        session()->flash('message', 'Usuário deletado com sucesso.');
    }
}; ?>

<div>
    <x-app-layout>
        <div class="container mx-auto p-4 sm:p-6 lg:p-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                Gerenciar Usuários
            </h1>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    @forelse ($this->users as $user)
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-4 @if (!$loop->last) border-b border-gray-200 dark:border-gray-700 @endif">
                            <div class="mb-4 sm:mb-0">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                <div class="mt-2">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="#"
                                    class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Editar</a>
                                <button wire:click="delete({{ $user->id }})"
                                    wire:confirm="Tem certeza que deseja deletar este usuário?"
                                    class="ml-4 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    Deletar
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                            Nenhum usuário encontrado.
                        </div>
                    @endforelse
                </div>

                @if ($this->users->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-gray-900">
                        {{ $this->users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-app-layout>
</div>
