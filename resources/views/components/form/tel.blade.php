@php
    $key = isset($key) ? $key : 'id-'.$name;
    $label = isset($label) ? $label : 'true';
    $value = $value ?? $default ?? old($name) ;
@endphp
<div class="mb-3 {{ $col ?? 'col-12' }}">
    @if(isset($label) && $label === 'true')
        <label for="{{ $key }}" class="form-label">
            {{ $labelName }}
            @isset($required) <sup class="text-danger">*</sup> @endisset
        </label>
    @endif

    <input type="tel"
           @isset($readonly) readonly @endisset
           @isset($disabled) disabled @endisset
           id="{{ $key }}"
           @isset($required) required @endisset
           class="form-control @isset($classes) {{ $classes }} @else {{ $name }} @endisset @error($name) is-invalid @enderror"
    >
    @error($name) <span class="text-danger fw-bold">{{ $message }}</span> @enderror

    <input type="hidden" name="{{ $name }}" id="formatted_{{ $key }}" value="{{ $value }}">
</div>

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
        $(document).ready(function () {
            const defaultPhoneNumber = "{{ $value }}";

            const phoneInput = $(".{{ $classes ?? $name }}");

            if (phoneInput.length === 0) {
                console.error("Phone input field not found.");
                return;
            }

            phoneInput.val(defaultPhoneNumber);

            const iti = window.intlTelInput(phoneInput[0], {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: function(success) {
                    $.get('https://ipinfo.io/json?token=<YOUR_TOKEN>', function() {}, "jsonp")
                        .done(function(resp) {
                            const countryCode = resp && resp.country ? resp.country : 'eg';
                            success(countryCode);
                        })
                        .fail(function() {
                            success('eg');
                        });
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            iti.setNumber(defaultPhoneNumber);

            phoneInput.on('blur', function() {
                let phoneNumber = iti.getNumber();
                if (phoneInput.val().trim()) {
                    if (iti.isValidNumber()) {
                        console.log("Phone number is valid:", phoneNumber);
                    } else {
                        console.log("Phone number is invalid:", phoneNumber);
                    }
                    $("#formatted_{{ $key }}").val(phoneNumber);
                }
            });

            $("form").on("submit", function() {
                const formattedPhoneNumber = iti.getNumber();
                if (formattedPhoneNumber) {
                    $("#formatted_{{ $key }}").val(formattedPhoneNumber);
                }
            });
        });
    </script>
@endpush
