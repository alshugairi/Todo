<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Admin | Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/overlayscrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.css">

    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/adminlte.rtl.css">
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/rtl.css">
    @else
        <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/adminlte.css">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/css/custom.css">
</head>

<body>
<div class="login--page w-100">
    <div class="row min-vh-100 p-0 m-0">
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center text-white" style="background:#1F2D3A;">
            <div class="text-center">
                <img src="{{ asset('assets/admin') }}/images/logo.png" alt="Logo" class="img-fluid mb-4 logo">
            </div>
        </div>

        <div class="col-md-6 d-flex align-items-center justify-content-center bg-light position-relative">
            <div class="language-switcher position-absolute" style="top: 20px; right: 20px;">
                <select class="form-select form-select-sm" onchange="switchLanguage(this)">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }} data-href="{{ route('language.switch', 'en') }}">{{ __('admin.english') }}</option>
                    <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }} data-href="{{ route('language.switch', 'ar') }}">{{ __('admin.arabic') }}</option>
                </select>
            </div>
            <div class="w-100">
                @include('flash::message')
                @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="px-180">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ asset('assets/admin') }}/js/overlayscrollbars.browser.es6.min.js"></script>
<script src="{{ asset('assets/admin') }}/js/popper.min.js"></script>
<script src="{{ asset('assets/admin') }}/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="{{ asset('assets/admin') }}/js/adminlte.js"></script>
<script>
    $(document).ready(function() {
        $(".jquery-validate").validate();

        $('.input-group-text').click(function() {
            const passwordInput = $(this).siblings('input');
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
    function switchLanguage(select) {
        const selectedOption = select.options[select.selectedIndex];
        const href = selectedOption.getAttribute('data-href');
        if (href) {
            window.location.href = href;
        }
    }
</script>
</body>
</html>
