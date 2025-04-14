@use(App\Helpers\TemplateHelper\PaginationHelper)
<div class="paginate my-3">
{{--    <div class="float-start">--}}
{{--        <select class="form-select d-inline-block">--}}
{{--            @foreach([10,25,50,100] as $perPage)--}}
{{--                <option @if(50 === $perPage) selected--}}
{{--                        @endif value="{{ $perPage }}">{{ $perPage }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}

    <div class="float-end">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>
