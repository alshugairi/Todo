<div id="tabs">
    @if(count($appLanguages) > 1)
        <ul class="nav nav-tabs" role="tablist">
            @foreach($appLanguages as $appLanguage)
                <li class="nav-item">
                    <a href="#navs-top-{{ $appLanguage->id }}"
                       type="button"
                       class="nav-link{{ $loop->first ? ' active' : '' }}"
                       role="tab"
                       data-bs-toggle="tab"
                       data-bs-target="#navs-top-{{ $appLanguage->id }}"
                       aria-controls="navs-top-{{ $appLanguage->id }}"
                       aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $appLanguage->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
    <div class="tab-content">
        {{ $slot }}
    </div>
</div>
