@props(['id', 'name', 'label' => null, 'value' => '', 'placeholder' => '', 'rows' => '7', 'cols' => '30'])
<div class="mb-4">
    @if ($label)
        <label class="block text-gray-700" for="{{ $id }}">{{ $label }}</label>
    @endif
    <textarea cols="30" rows="7" id="{{ $id }}" name="{{ $name }}"
        class="w-full px-4 py-2 border rounded focus:outline-none @error('description') border-red-500 @enderror"
        placeholder="{{ $placeholder }}" {{ old($name, $value) }}></textarea>
    @error('title')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
