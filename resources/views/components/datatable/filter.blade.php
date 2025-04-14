<button class="btn btn-md btn-warning-light" id="filterBtn">
    <i class="fa-solid fa-filter"></i>
    {{ __('admin.filter') }}
</button>
<div class="offcanvas" id="filterSidebar">
    <div class="offcanvas-header">
        <h5 id="offcanvasEndLabel" class="offcanvas-title text-center p-2">{{ __('admin.filter_options') }}</h5>
        <button type="button" class="close" id="closeSidebar" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="offcanvas-body p-4">
        <form id="filterForm">
            {{ $form }}
            <button type="submit" class="btn btn-primary mb-2 d-grid w-100">{{ __('admin.apply_filter') }}</button>
{{--            <button--}}
{{--                type="button"--}}
{{--                class="btn btn-label-secondary d-grid w-100"--}}
{{--                data-bs-dismiss="offcanvas">--}}
{{--                {{ __('share.cancel') }}--}}
{{--            </button>--}}
        </form>
    </div>
</div>
