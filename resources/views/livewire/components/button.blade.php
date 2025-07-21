<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $text = '';
    public string $class = '';
    public string $color = 'primary';
    public array $colors = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
    ];
}; ?>

<button type="button"
    class="cursor-pointer font-medium rounded-full text-sm px-5 py-2.5 text-center focus:outline-none {{ $colors[$color] }} {{ $class }}">
    {{ $text }}
</button>
