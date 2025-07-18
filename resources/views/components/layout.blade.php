@props(['size' => 'default', 'withCard' => false, 'withPadding' => true])

<x-app-layout {{ $attributes }}>
    @if (isset($header))
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endif

    <x-container :size="$size" :withCard="$withCard" :withPadding="$withPadding">
        {{ $slot }}
    </x-container>
</x-app-layout>
