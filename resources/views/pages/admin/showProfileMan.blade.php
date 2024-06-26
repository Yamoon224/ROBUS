<!DOCTYPE html>
<!--
Template Name: Rubick - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Rubick admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Rubick Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>Dashboard Commercial</title>
    <!-- BEGIN: CSS Assets-->
    
    <link rel="stylesheet" href="{{asset('public/dist/css/app.css') }}" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="py-5">
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="" class="flex mr-auto">
                <img alt="SIIKA" class="w-6" src="{{ asset('public/dist/images/logo.svg') }}">
            </a>
            <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2"
                    class="w-8 h-8 text-white transform -rotate-90"></i> </a>
        </div>
    </div>
    <!-- END: Mobile Menu -->
    <!-- BEGIN: Top Bar -->
    <div class="border-b border-white/[0.08] -mt-10 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 pt-3 md:pt-0 mb-10">
        <div class="top-bar-boxed flex items-center">
            <!-- BEGIN: Logo -->
            <a href="" class="-intro-x hidden md:flex">
                <img alt="" class="w-6" src="{{ asset('public/dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"> SIK-SIIKA </span>
            </a>
            <!-- END: Logo -->
            <!-- BEGIN: Breadcrumb -->
            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    @if (Auth::guard('web')->user()->hasRole('Commerciaux'))
                        <li class="breadcrumb-item"><a href="{{ route('managers.browse') }}">Application</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('movements.browse') }}">Application</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
                </ol>
            </nav>
            <!-- END: Breadcrumb -->
          
            
            <!-- BEGIN: Account Menu -->
            <div class="intro-x dropdown w-8 h-8">
                <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110"
                    role="button" aria-expanded="false" data-tw-toggle="dropdown">
                    <img alt="SIIKA"
                        src="{{ asset(Auth::guard('web')->user()->image ?? 'dist/images/profile-7.jpg') }}">
                </div>
                <div class="dropdown-menu w-56">
                    <ul
                        class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                        <li class="p-2">
                            <div class="font-medium"> {{ Auth::guard('web')->user()->firstname }}
                                {{ Auth::guard('web')->user()->lastname }}</div>
                        </li>
                        <li>
                            <a href="{{ route('account.profile.manager') }}" class="dropdown-item hover:bg-white/5">
                                <i data-feather="user" class="w-4 h-4 mr-2"></i> Profile </a>
                        </li>
                        <li>
                            <a class="dropdown-item hover:bg-white/5" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="me-50" data-feather="power"></i>
                                Se déconnecter
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;" id="logout-form">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END: Account Menu -->
        </div>
    </div>
    <!-- END: Top Bar -->
    <!-- BEGIN: Content -->
    <div class="content">
        <div class="grid grid-cols-12 gap-6 mt-10">

            <div class="intro-y col-span-12 lg:col-span-2">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">

                </div>
                <!-- END: Form Validation -->
            </div>

            <div class="intro-y col-span-12 lg:col-span-8">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">
                    <!-- BEGIN: Profile Info -->
                    <div class="intro-y box px-5 pt-5 mt-5">
                        <div
                            class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                <a href="{{ URL::previous() }}" class="btn btn-sm">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                        stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                        class="css-i6dzq1">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                </a>
                            </h2>
                            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                <label class="form-check-label ml-0" for="show-example-5"> Information du profil
                                </label>
                            </div>
                        </div>
                        <form class="validate-form pt-50" method="POST"
                            action="{{ route('account.update.manager') }}" enctype="multipart/form-data">
                            @csrf
                            <div
                                class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5 mt-3">
                                <div class="flex flex-1 px-5  sm:ml-auto items-center justify-center lg:justify-start">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                                        <img alt="" class="rounded-full"
                                            src="{{ asset(Auth::user()->image ?? 'public/dist/images/profile-6.jpgg') }}"
                                            alt="image" id="profileImage">
                                        <div class="mx-auto cursor-pointer relative mt-5">
                                            <input id="imageUpload" type="file" name="image" placeholder="Photo" capture
                                                style="display: none" accept="image/png, image/jpeg">
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="ml-5">
                                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                                            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                                    <div class="font-medium text-center lg:text-left lg:mt-3">Details</div>
                                    <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                                        <div class="truncate sm:whitespace-normal flex items-center"> <i
                                                data-feather="mail" class="w-4 h-4 mr-2"></i>
                                            {{ Auth::user()->email }} </div>
                                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i
                                                data-feather="instagram" class="w-4 h-4 mr-2"></i>
                                            {{ Auth::user()->phone }} </div>
                                    </div>
                                </div>
                            </div>
                            @include('components.account-navManager')
                    </div>
                    <!-- END: Profile Info -->
                </div>
                <!-- END: Form Validation -->

                <div class="tab-content mt-2">
                    <div id="profile" class="tab-pane active" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="grid grid-cols-12 gap-6">
                            <!-- BEGIN: Latest Uploads -->
                            <div class="intro-y box col-span-12">
                                <div class="p-5">

                                    <div class="crud-form-1 mt-3">
                                        <label class="form-label" for="firstname">Prénom(s)</label>
                                        <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                            id="firstname" name="firstname" placeholder="" required
                                            value="{{ Auth::user()->firstname }}" />
                                        @error('firstname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="crud-form-1 mt-3">
                                        <label class="form-label" for="firstname">Nom</label>
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                            id="lastname" name="lastname" placeholder="" required
                                            value="{{ Auth::user()->lastname }}" />
                                        @error('lastname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="crud-form-1 mt-3">
                                        <label class="form-label" for="phone">Numéro de téléphone <span
                                                id="valid_phone"></span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" placeholder="" required
                                            value="{{ Auth::user()->phone }}" />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="crud-form-1 mt-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="" required
                                            value="{{ Auth::user()->email }}" />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="crud-form-1 intro-y mt-3">
                                        <label class="form-label" for="country">Pays </label>
                                        <select required id="country_id" name="country_id"
                                            class="select2 form-select @error('country_id') is-invalid @enderror">
                                            @foreach ($countries as $countrie)
                                                <option value="{{ $countrie->id }}"
                                                    @if ($countrie->id === Auth::user()->country_id) selected @endif
                                                    value="{{ $countrie->id }}">{{ $countrie->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary mt-1 me-1">Mettre à jour</button>
                                    </div>
                                </div>
                                </form>
                                <!--/ form -->
                            </div>
                        </div>
                        <!-- END: Latest Uploads -->
                    </div>
                </div>
            </div>


        </div>

        <div class="intro-y col-span-12 lg:col-span-2">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
            <!-- END: Form Validation -->
        </div>
    </div>

    <!-- END: Content -->
    <!-- END: Dark Mode Switcher-->
    <script src="{{asset('public/dist/js/jquery.js') }}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('public/dist/js/script.js') }}"></script>
    <script src="{{asset('public/dist/js/app.js') }}"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=[" your-google-map-api"]&libraries=places"></script>
</body>

</html>
