@props(['for'])

@error($for)
    <span {{ $attributes->merge(['class' => 'font-medium tracking-wide text-red-500 text-xs mt-1 ml-1']) }}>{{ $message }}</span>
@enderror
