<div class="form-check form-check-custom form-check-solid mb-5">
    <input class="form-check-input me-3" name="{{$name}}" value="{{$value}}" type="radio" id="{{$key}}"/>
    <label class="form-check-label" for="{{$key}}">
        <div class="fw-bold text-gray-800">{{$labelName}}</div>
        @isset($description)
        <div class="text-gray-600">{{$description}}</div>
        @endisset
    </label>
</div>
