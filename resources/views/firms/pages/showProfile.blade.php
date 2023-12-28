@extends('layouts.master_company')

@section('title', 'Compte')

@section('content')

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
                    <div class="intro-y  items-center mt-3">
                        <h2 class="text-lg font-medium mr-auto">
                            Information du profil
                        </h2>
                    </div>

                    <form class="validate-form pt-50" method="POST" action="{{ route('employers.account.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div
                            class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                                    <img alt="" class="rounded-full"
                                        src="{{ asset(firm()->image ?? 'public/dist/images/profile-6.jpg') }}"
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
                                        {{ firm()->firstname }} {{ firm()->lastname }}
                                    </div>
                                </div>
                                <div class="ml-5">
                                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">

                                        Code Wallet :
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ firm()->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                                <div class="font-medium text-center lg:text-left lg:mt-3">Details</div>
                                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                                            class="w-4 h-4 mr-2"></i> {{ firm()->email }} </div>
                                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i
                                            data-feather="instagram" class="w-4 h-4 mr-2"></i> {{ firm()->phone }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('components.update_profil_company')
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
                                        value="{{ firm()->firstname }}" />
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
                                        value="{{ firm()->lastname }}" />
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
                                        value="{{ firm()->phone }}" />
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
                                        value="{{ firm()->email }}" />
                                    @error('email')
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

@endsection

@push('scripts')
@endpush
