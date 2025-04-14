@php($key = isset($key) ? $key : 'id-'.$name)
@php($label = isset($label) ? $label : 'true')
<div class="mb-3 {{ $col ?? "col-12" }}">
    @if(isset($label) && $label ==='true')
        <label for="{{ $key }}" class="form-label">
            {{ $labelName }}
            @isset($required) <sup class="text-danger">*</sup> @endisset
        </label>
    @endif
    @php($inputValue = $value ?? old($name))
    <select name="{{ $name }}"
            @isset($multiple)
                multiple="multiple"
            @endisset
            @isset($required) required @endisset
            @if(isset($isDisable) && $isDisable) disabled @endif
            id="{{ $key }}"
            class="form-control form-select select2 @isset($classes) {{ $classes }} @else {{ $name }} @endisset @error($name) is-invalid @enderror " data-placeholder="@lang('admin.select') {{ $labelName }}"
            data-control="select2">
        <option value="">@lang('admin.select')</option>
        @isset($options)
            @foreach($options as $keyValue => $valueName)
                <option value="{{ $keyValue }}" @selected(($inputValue == $keyValue) || (isset($multiple) && is_string($value) && strpos($value, ',') !== false && in_array($keyValue, explode(',', $value))))>{{ $valueName }}</option>
            @endforeach
        @endisset
        @if(isset($select2) && $select2 && isset($value) && is_array($value))
            <option value="{{ $value['id'] }}" selected>{{ $value['text'] }}</option>
        @endif
    </select>
    @error($name) <span class="text-danger fw-bold">{{  $message  }}</span> @enderror
</div>
