<div class="dropdown d-inline-block">
    <button
        class="dt-button btn btn-sm btn-outline-primary waves-effect waves-light dropdown-toggle"
        type="button"
        id="budgetId"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        {{ __('share.actions') }}
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="budgetId">
        {{ $slot }}
    </div>
</div>
