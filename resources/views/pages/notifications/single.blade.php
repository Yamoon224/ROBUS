@extends('layouts.master')
<script src="https://cdn.ckeditor.com/4.19.0/standard-all/ckeditor.js"></script>

@section('title', 'Notifications')

@section('content')
    <!-- End layout styles -->
    {{-- <link rel="stylesheet" href="{{ asset('dist/css/aiz-core.css') }}" /> --}}
    <!-- BEGIN: General Report -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        @if (Auth::guard('web')->user()->hasPermissionTo('voir-commandes'))
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table1">
                        @php
                            
                            $today = Illuminate\Support\Carbon::today();
                            
                            $resOrdersIn = App\Models\Payment::join('users', 'user_id', 'users.id')
                                ->join('orders', 'order_id', 'orders.id')
                                ->whereDate('users.created_at', '=', $today)
                                ->get();
                        @endphp
                        <div class="flex">
                            <i data-feather="user" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{ $resOrdersIn->count() ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Nouveaux clients</div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::guard('web')->user()->hasPermissionTo('voir-commandes'))
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table2">
                        @php
                            
                            $resOrdersIn = App\Models\Payment::join('users', 'user_id', 'users.id')
                                ->join('orders', 'order_id', 'orders.id')
                                ->where('orders.progress_order', 'In-progress')
                                ->get();
                        @endphp
                        <div class="flex">
                            <i data-feather="shopping-bag" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{ $resOrdersIn->count() ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">En-cours de paiement</div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::guard('web')->user()->hasPermissionTo('voir-commandes'))
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table3">
                        @php
                            
                            $resOrdersDelivery = App\Models\Payment::join('users', 'user_id', 'users.id')
                                ->join('orders', 'order_id', 'orders.id')
                                ->where('orders.progress_order', 'Out_for_delivery')
                                ->get();
                        @endphp
                        <div class="flex">
                            <i data-feather="shopping-bag" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{ $resOrdersDelivery->count() }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">En-attente de livraisons</div>
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('voir-commandes'))
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        @php
                            
                            $resOrdersFinished = App\Models\User::get();
                        @endphp
                        <div class="flex">
                            <i data-feather="users" class="report-box__icon text-primary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">{{ $resOrdersFinished->count() ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Total clients</div>
                    </div>
                </div>
            </div>
        @endif
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-3 mb-6">
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class=" sm:mr-auto mt-3 sm:mt-0  md:ml-0">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Filtrer les nouveaux clients </label>
                    <form class="" id="sort_users" action="" method="GET">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="new_users" onchange="sort_users()" data-selected="{{ $new_users }}">
                                <option value="">Filtrer par livraison</option>
                                <option>Nouveaux clients</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class=" sm:mr-auto mt-3 sm:mt-0  md:ml-0">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Filtrer les clients qui ne payent pas depuis 30 jours </label>
                    <form class="" id="all_days" action="" method="GET">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="all_30_days" onchange="all_days()" data-selected="{{ $all_30_days }}">
                                <option value="">Filtrer par clients</option>
                                <option value="all_30_days">Clients qui ne payent pas depuis 30 jours</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class=" sm:mr-auto mt-3 sm:mt-0  md:ml-0">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Filtrer les clients en attente de livraison</label>
                    <form class="" id="all_users_delivery" action="" method="GET">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="all_users_for_delivery" onchange="all_users_delivery()"
                                data-selected="{{ $all_users_for_delivery }}">
                                <option value="">Filtrer par livraison</option>
                                <option>Clients en attente de livraison</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class=" sm:mr-auto mt-3 sm:mt-0  md:ml-0">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Filtrer tout les clients </label>
                    <form class="" id="all_users_days" action="" method="GET">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="all_users" onchange="all_users_days()" data-selected="{{ $all_users }}">
                                <option value="">Filtrer par livraison</option>
                                <option>Tout les clients</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- END: General Report -->
    <div class="grid grid-cols-12 gap-6">

        <div class="intro-y col-span-12 lg:col-span-2">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
            <!-- END: Form Validation -->
        </div>

        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="flex items-center  px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                <div class="bg-teal-100 alert-primary border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">La notification par defaux est pour les nouveaux</p>
                            <p class="text-sm">Pour plus faites par filtrages.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
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
                        <label class="form-check-label ml-0" for="show-example-5"> Notification</label>
                    </div>
                </div>
                <div class="p-5">
                    <form class="validate-form" method="POST" action="{{ route('notifications.single_notif') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <span id="errorMail" style="color:green; font-weight:bolder; padding:5px">
                            @if (\Session::has('msg'))
                                {!! \Session::get('msg') !!}
                            @endif
                        </span>

                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-6" style="display: none;">
                            <label class="form-label" for="user_emails">Email(Clients) <span class="text-danger">
                                    *</span></label>
                            <select id="user_emails" name="user_emails[]" data-placeholder="Selectionne les emails"
                                class="tom-select w-full @error('user_emails') is-invalid @enderror" multiple>
                                <option disabled selected></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->email }}" selected>
                                        {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label for="subject" class="form-label"> Objet <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control col-span-6 @error('subject') is-invalid @enderror"
                                id="subject" name="subject" placeholder="" rows="3" cols="5">
                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label for="message" class="form-label"> Messages <span class="text-danger">*</span>
                            </label>
                            <textarea type="text" class="form-control @error('message') is-invalid @enderror" id="editor" name="message"
                                placeholder="" rows="10">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-5"> <i
                                class="fa fa-bell mr-3"></i>Envoyer</button>
                    </form>
                    <!-- END: Validation Form -->
                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>

@endsection



@push('scripts')
    <script type="text/javascript">
        CKEDITOR.replace('message', {
            filebrowserUploadUrl: "{{ route('about.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            extraPlugins: 'embed,autoembed,image2',
            height: 500,

            // Load the default contents.css file plus customizations for this sample.
            contentsCss: [
                'http://cdn.ckeditor.com/4.19.0/full-all/contents.css',
                'https://ckeditor.com/docs/ckeditor4/4.19.0/examples/assets/css/widgetstyles.css'
            ],
            // Setup content provider. See https://ckeditor.com/docs/ckeditor4/latest/features/media_embed
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',

            // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
            // resizer (because image size is controlled by widget styles or the image takes maximum
            // 100% of the editor width).

        });
    </script>
    <script type="text/javascript">
        function sort_users(el) {
            $('#sort_users').submit();
        }

        function all_users_delivery(el) {
            $('#all_users_delivery').submit();
        }

        function new_users(el) {
            $('#new_users').submit();
        }


        function all_users_days(el) {
            $('#all_users_days').submit();
        }

        function all_days(el) {
            $('#all_days').submit();
        }
    </script>
@endpush
