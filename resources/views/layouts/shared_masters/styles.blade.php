<link rel="shortcut icon" type="image/x-icon" href="{{ asset_url('brand/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
    rel="stylesheet">

<!-- endinject -->
<!-- Plugin css for this page -->

<link href="{{ asset_url('dist/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
{{-- <link href="/path/to/css//css/fileinput.min.css" rel="stylesheet"> --}}
{{-- <link href="/path/to/css//css/fileinput-rtl.min.css" rel="stylesheet"> --}}

<!-- End plugin css for this page -->
<!-- inject:css -->
<!-- endinject -->

<!-- Layout styles -->
<!-- End layout styles -->
<link rel="stylesheet" href="{{ asset_url('css/masters/app.css') }}" />
<!-- END: Page CSS-->

{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

<!-- BEGIN: Custom CSS-->

@include('layouts.partials.page_frame')
