<button type="button"
        class="btn btn-sm {{ ($header ?? false) ? 'btn-primary':'btn-light-primary' }} btn-flex fw-bold me-3"
        data-bs-toggle="modal"
        data-bs-target="#kt_modal_add">
    {{ $module }}
</button>
<div class="modal fade" id="kt_modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold"> {{ $module }}</h2>

                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                    {{--                     data-bs-dismiss="modal" --}}
                    {{--                     aria-label="Close"--}}
                >
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                {{$form}}
            </div>
        </div>
    </div>
</div>
