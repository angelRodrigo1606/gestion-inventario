<label for="{{ $name }}" class="block text-sm font-medium text-secondary-label mb-1">
    {{ $label }}
</label>
<input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    value="{{ $value ?? '' }}"
    {!! $attrs ?? '' !!}
    class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
        {{ $errors->has($name) ? 'border-system-red' : 'border-separator' }}"
>
@error($name)
    <p class="text-system-red text-xs mt-1">{{ $message }}</p>
@enderror
