{{--<a wire:click="$emit('deleting', {{ $id }} , '{{ $module }}')"--}}
{{--   class="btn btn-sm btn-light-danger mb-2">--}}
{{--    <x-svg.delete/>--}}
{{--    @lang('share.Delete')--}}
{{--</a>--}}

<a class="dropdown-item" href="{{route($route,$id)}}"><i class="ti ti-trash me-1"></i> {{ __('share.delete') }}</a>
