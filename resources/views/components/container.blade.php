@props(['size' => 'default', 'withCard' => false, 'withPadding' => true])

@php
    $containerClasses = [
        'default' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
        'sm' => 'max-w-2xl mx-auto px-4 sm:px-6 lg:px-8',
        'lg' => 'max-w-full mx-auto px-4 sm:px-6 lg:px-8',
        'fluid' => 'w-full px-4 sm:px-6 lg:px-8',
    ];

    $wrapperClass = $withPadding ? 'py-12' : '';
@endphp

<div @if ($withPadding) class="{{ $wrapperClass }}" @endif>
    <div {{ $attributes->merge(['class' => $containerClasses[$size]]) }}>
        @if ($withCard)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ $slot }}
                </div>
            </div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
