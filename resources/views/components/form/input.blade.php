@php($key = isset($key) ? $key : 'id-'.$name)
@php($label = isset($label) ? $label : 'true')
@php($maxLength = $maxLength ?? null)

<div class="mb-3 {{ $col ?? "col-12" }}">
    @if(isset($label) && $label ==='true')
        <label for="{{ $key }}" class="form-label {{ $labelClass ?? '' }}">
            {{ $labelName }}
            @isset($required) <sup class="text-danger">*</sup> @endisset
        </label>
    @endif

    @php($type = $type ?? 'text')

    <input type="{{$type}}"
           @if(isset($name)) name="{{ $name }}" @endif
           @isset($readonly) readonly @endisset
           @isset($disabled) disabled @endisset
           id="{{ $key }}"
           @isset($required) required @endisset
           @if($type=='number') min="0" @endif
           @isset($step) step="{{ $step }}" @endif
           class="form-control @isset($classes) {{ $classes }} @else {{ $name }} @endisset @error($name) is-invalid @enderror"
           @isset($multiple) multiple @endisset
           @isset($maxLength) maxlength="{{ $maxLength }}" @endisset
           value="{{ $value ?? $default ?? old($name) }}"
           oninput="updateCharCount('{{ $key }}', {{ $maxLength ?? 0 }})"
    >

    @isset($maxLength)
        <small id="{{ $key }}-char-count" class="form-text text-muted">
            <span id="{{ $key }}-remaining">{{ $maxLength }}</span> {{ __('characters remaining') }}
        </small>
    @endisset

    @error($name) <span class="text-danger fw-bold">{{ $message }}</span> @enderror
</div>

<script>
    function updateCharCount(inputId, maxLength) {
        let inputField = document.getElementById(inputId);
        let remainingSpan = document.getElementById(inputId + '-remaining');
        if (inputField && remainingSpan) {
            let remaining = maxLength - inputField.value.length;
            remainingSpan.textContent = remaining >= 0 ? remaining : 0;
        }
    }
</script>
