<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $placeholder = '';
}; ?>

<div class="card col-span-12 p-4">
    <div class="grid grid-cols-4 gap-4">
        <div class="col-span-3">
            <x-input name="search" placeholder="{{ $placeholder }}" required />
        </div>
        <livewire:components.button class="col-span-1" text="Pesquisar" />
    </div>
</div>
