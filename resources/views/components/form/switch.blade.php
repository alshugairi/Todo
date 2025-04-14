@php($key = isset($key) ? $key : 'id-'.$name)
@php($label = isset($label) ? $label : 'true')
@php($inputValue = $value ? (int) $value : old($name))
@php($defaultValue = $default ?? 1)

<div class="mb-3 {{ $col ?? "col-12" }}">
    @if(isset($label) && $label ==='true')
        <label for="{{ $key }}" class="form-label">
            {{ $labelName }}
            @isset($required) <sup class="text-danger">*</sup> @endisset
        </label>
    @endif

        <div class="form-check form-switch mb-2">
            <input type="checkbox" @checked($inputValue === $defaultValue)
                class="form-check-input @isset($classes) {{ $classes }} @else {{ $name }}" @endisset "
                name="{{$name}}" value="{{ $defaultValue }}"
                id="{{ $key }}"/>
        </div>
        @error($name) <span class="text-danger fw-bold">{{ $message }}</span> @enderror
</div>


