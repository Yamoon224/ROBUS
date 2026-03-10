<link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/dist/images/logo_orange.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
    rel="stylesheet">

<!-- Plugin css for this page -->
<link href="{{ asset_url('dist/plugins/toastr/css/toastr.min.css') }}?v={{ filemtime(public_path('dist/plugins/toastr/css/toastr.min.css')) }}" 
      rel="stylesheet">

<!-- Layout styles -->
{{-- <link rel="stylesheet" 
      href="{{ asset_url('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}"> --}}

<!-- App styles -->
<link rel="stylesheet" 
      href="{{ asset_url('dist/css/app.css') }}?v={{ filemtime(public_path('dist/css/app.css')) }}" />

<!-- Animation styles -->
<link rel="stylesheet" 
      href="{{ asset_url('web/css/animation.css') }}?v={{ filemtime(public_path('web/css/animation.css')) }}">
