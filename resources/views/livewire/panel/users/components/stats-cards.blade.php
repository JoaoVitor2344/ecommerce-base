<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component {
    #[On('user-deleted')]
    #[On('user-updated')]
    public function refreshStats()
    {
        // Força recarregamento das estatísticas
    }

    public function filterByActive()
    {
        $this->dispatch('apply-quick-filter', ['status' => 'active']);
    }

    public function filterByPending()
    {
        $this->dispatch('apply-quick-filter', ['status' => 'inactive']);
    }

    public function filterByAdmins()
    {
        $this->dispatch('apply-quick-filter', ['role' => 'admin']);
    }

    public function filterByTotal()
    {
        $this->dispatch('apply-quick-filter', ['clear' => true]);
    }

    public function with(): array
    {
        return [
            'stats' => [
                'total' => User::count(),
                'active' => User::whereNotNull('email_verified_at')->count(),
                'pending' => User::whereNull('email_verified_at')->count(),
                'admins' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->count(),
            ],
        ];
    }
}; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total de Usuários -->
    <div class="card-bg stats-card group" x-data="statsCard({{ $stats['total'] }}, 1000, 100)" x-init="start()"
        @click="$wire.dispatch('filter-by-total')">
        <div class="flex items-center">
            <div
                class="p-3 rounded-full bg-accent-content text-accent-foreground group-hover:bg-accent transition-colors">
                <flux:icon.users class="h-6 w-6" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total de Usuários</p>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-white transition-all">
                    <span x-text="Math.floor(count)"></span>
                </p>
            </div>
        </div>
        <div class="mt-6 text-xs text-zinc-500 dark:text-zinc-400 opacity-0 group-hover:opacity-100 transition-opacity">
            Clique para ver todos
        </div>
    </div>

    <!-- Usuários Ativos -->
    <div class="card-bg stats-card group" x-data="statsCard({{ $stats['active'] }}, 1200, 200)" x-init="start()"
        @click="$wire.dispatch('filter-by-active')">
        <div class="flex items-center">
            <div
                class="p-3 rounded-full bg-accent-content text-accent-foreground group-hover:bg-accent transition-colors">
                <flux:icon.check-circle class="h-6 w-6" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Usuários Ativos</p>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-white">
                    <span x-text="Math.floor(count)"></span>
                </p>
            </div>
        </div>
        <div class="mt-6 text-xs text-zinc-500 dark:text-zinc-400 opacity-0 group-hover:opacity-100 transition-opacity">
            Clique para filtrar ativos
        </div>
    </div>

    <!-- Usuários Pendentes -->
    <div class="card-bg stats-card group" x-data="statsCard({{ $stats['pending'] }}, 1400, 400)" x-init="start()"
        @click="$wire.dispatch('filter-by-pending')">
        <div class="flex items-center">
            <div
                class="p-3 rounded-full bg-accent-content text-accent-foreground group-hover:bg-accent transition-colors">
                <flux:icon.clock class="h-6 w-6" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Pendentes</p>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-white">
                    <span x-text="Math.floor(count)"></span>
                </p>
            </div>
        </div>
        <div class="mt-6 text-xs text-zinc-500 dark:text-zinc-400 opacity-0 group-hover:opacity-100 transition-opacity">
            Clique para filtrar pendentes
        </div>
    </div>

    <!-- Administradores -->
    <div class="card-bg stats-card group" x-data="statsCard({{ $stats['admins'] }}, 1600, 600)" x-init="start()"
        @click="$wire.dispatch('filter-by-admins')">
        <div class="flex items-center">
            <div
                class="p-3 rounded-full bg-accent-content text-accent-foreground group-hover:bg-accent transition-colors">
                <flux:icon.shield-check class="h-6 w-6" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Administradores</p>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-white">
                    <span x-text="Math.floor(count)"></span>
                </p>
            </div>
        </div>
        <div class="mt-6 text-xs text-zinc-500 dark:text-zinc-400 opacity-0 group-hover:opacity-100 transition-opacity">
            Clique para filtrar admins
        </div>
    </div>
</div>

<script>
    function statsCard(target, duration, delay) {
        return {
            count: 0,
            start() {
                time = 100
                setTimeout(() => {
                    const step = target / (duration / time);
                    const timer = setInterval(() => {
                        this.count += step;
                        if (this.count >= target) {
                            this.count = target;
                            clearInterval(timer);
                        }
                    }, time);
                }, delay);
            }
        }
    }
</script>