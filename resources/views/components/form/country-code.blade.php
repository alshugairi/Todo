@push('css')
    <link rel="stylesheet" href="{{ secure_asset('countries-codes/build/css/countrySelect.css') }}">
@endpush
<div class="mb-5 {{ $col ?? "col-12" }}">
    <label class="fw-semibold fs-6 mb-2">{{ $labelName }}</label>
   <div>
       <input class="form-control form-control-solid iso_country" type="text" id="country"/>
       <input class="form-control form-control-solid" type="hidden" id="country_code"/>
   </div>
</div>
@push('scripts')
    <script src="{{ asset('countries-codes/build/js/countrySelect.min.js') }}"></script>
    <script>
        $(".iso_country").countrySelect({
            defaultCountry: "eg",
            preferredCountries: ['eg', 'sa', 'us'],
        });
        @if(isset($this->iso) && !empty($this->iso))
        $(".iso_country").countrySelect("selectCountry", "{{ $this->iso }}");
        @endif
      $(function (){
          $('.iso_country').on('change',function (){
          @this.set('iso', $('#country_code').val());
          });
      })
    </script>
@endpush

