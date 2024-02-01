@extends('layouts.master_master')

@section('title', 'Changé de mot de passe')

@section('content')

    <div class="grid grid-cols-12 gap-6 mt-10">

        <div class="intro-y col-span-12 lg:col-span-3">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
            <!-- END: Form Validation -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <!-- BEGIN: Profile Info -->
                <div class="intro-y box px-5 pt-5 mt-5">
                    @include('components.update_profil_master')
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
                                <form class="validate-form" method="POST" action="{{ route('masters.account.security.update') }}">
                                    @csrf
                                    <div class="crud-form-1 mt-3" x-data="{ show: true }">
                                        <label class="form-label" for="current_password">Mot de passe actuel</label>
                                        <div class="input-group form-password-toggle input-group-merge">
                                            <input placeholder="" :type="show ? 'password' : 'text'" id="current_password"
                                                name="current_password" placeholder="Mot de passe actuel"
                                                class="form-control
                                        focus:placeholder-gray-500
                                        focus:bg-white 
                                        focus:border-gray-600  
                                        focus:outline-none @error('current_password') is-invalid @enderror">
                                            <div class="input-group-text cursor-pointer">
                                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                                    :class="{ 'hidden': !show, 'block': show }"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 576 512">
                                                    <path fill="currentColor"
                                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                    </path>
                                                </svg>

                                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                                    :class="{ 'block': !show, 'hidden': show }"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 640 512">
                                                    <path fill="currentColor"
                                                        d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                                    </path>
                                                </svg>

                                            </div>
                                        </div>
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="crud-form-1 mt-3" x-data="{ show: true }">
                                        <label class="form-label" for="new_password">Nouveau mot de passe</label>
                                        <div class="input-group form-password-toggle input-group-merge">
                                            <input placeholder="" :type="show ? 'password' : 'text'" id="new_password"
                                                name="new_password" placeholder="Nouveau mot de passe"
                                                class="form-control
                                        focus:placeholder-gray-500
                                        focus:bg-white 
                                        focus:border-gray-600  
                                        focus:outline-none @error('new_password') is-invalid @enderror">
                                            <div class="input-group-text cursor-pointer">
                                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                                    :class="{ 'hidden': !show, 'block': show }"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 576 512">
                                                    <path fill="currentColor"
                                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                    </path>
                                                </svg>
                                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                                    :class="{ 'block': !show, 'hidden': show }"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 640 512">
                                                    <path fill="currentColor"
                                                        d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="crud-form-1 mt-3" x-data="{ show: true }">
                                        <label class="form-label" for="new_confirm_password">Confirmation du nouveau mot de
                                            passe</label>
                                        <div class="input-group form-password-toggle input-group-merge">
                                            <input placeholder="" :type="show ? 'password' : 'text'"
                                                id="new_confirm_password" name="new_confirm_password"
                                                placeholder="Confirmer le mot de passe"
                                                class="form-control
                                        focus:placeholder-gray-500
                                        focus:bg-white 
                                        focus:border-gray-600  
                                        focus:outline-none @error('new_confirm_password') is-invalid @enderror">
                                            <div class="input-group-text cursor-pointer">
                                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                                    :class="{ 'hidden': !show, 'block': show }"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 576 512">
                                                    <path fill="currentColor"
                                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                    </path>
                                                </svg>
                                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                                    :class="{ 'block': !show, 'hidden': show }"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 640 512">
                                                    <path fill="currentColor"
                                                        d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
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

    <div class="intro-y col-span-12 lg:col-span-3">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

        </div>
        <!-- END: Form Validation -->
    </div>
    </div>

@endsection

@push('scripts')
@endpush
