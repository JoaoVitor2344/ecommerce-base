<div class="relative w-full">
    <input type="text" id="voice-search"
        class="bg-gray-50 border border-neutral-200 text-gray-900 text-sm rounded-lg focus:ring-neutral-500 focus:border-neutral-500 block w-full ps-10 p-2.5 outline-none dark:bg-zinc-900 dark:border-neutral-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-neutral-500 dark:focus:border-neutral-500 {{ $class ?? '' }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}" 
        required="{{ $required ?? false }}" 
    />
</div>
