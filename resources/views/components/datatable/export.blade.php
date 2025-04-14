<div class="btn-group">
    <button type="button" class="btn btn-md btn-secondary-light dropdown-toggle" id="filterBtn" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-cloud-arrow-down"></i>
        {{ __('admin.export') }}
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="#" id="printOption">
                <i class="fa-solid fa-print"></i> {{ __('admin.print') }}
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" id="excelOption">
                <i class="fa-solid fa-file-excel"></i> {{ __('admin.excel') }}
            </a>
        </li>
    </ul>
</div>

