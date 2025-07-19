<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <div class="grid auto-rows-min gap-4 md:grid-cols-12">
                {{ $slot }}
            </div>
        </div>
    </flux:main>
</x-layouts.app.sidebar>
