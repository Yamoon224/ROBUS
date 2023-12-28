@extends('layouts.master')

@section('title', 'Details carte')

@section('content')

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur la carte {{ $card->phone }}
        </h2>
        <h2 class="font-medium text-base mr-auto mt-3 mb-3">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                    stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>
        <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                <img alt="Midone - HTML Admin Template"
                    src="{{ asset($card->wallet->sidUser->image ?? 'public/dist/images/profil.png') }}">
            </div>

        </div>
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">

                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg" style="width: 20rem;">
                        {{ $card->email ?? null }}
                    </div>
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-3" style="width: 20rem;">
                        {{ $card->wallet->sidUser->firstname ?? null }}   {{ $card->wallet->sidUser->lastname ?? null }}
                    </div>
                </div>

                <div class="ml-5">

                    @if ($card->status == true)

                        <div class=" truncate sm:whitespace-normal font-medium text-lg mt-3 text-success">
                            <a class="flex items-center mr-3 mb-3 mt-3">
                                <i class="fas fa-unlock-alt w-4 h-4 mr-2 "></i>
                                Validez 
                            </a>
                        </div>
                         <div class=" truncate sm:whitespace-normal font-medium text-lg mt-3 text-success">
                            <a class="flex items-center mr-3 mb-3 mt-3">
                                <i class="fas fa-unlock-alt w-4 h-4 mr-2 "></i>
                                Id : {{ $card->ref_card ?? null }}
                            </a>
                        </div>
                    @else
                        <div class=" truncate sm:whitespace-normal font-medium text-lg mt-3">
                            <form class="validate-form" method="POST"
                                action="{{ route('cards.update_cards', ['id' => $card->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="crud-form-1 intro-y col-span-12 lg:col-span-6">
                                    <label class="form-label" for="ref_card">Id carte <span class="text-danger">
                                            *</span></label>
                                    <input type="tel" class="form-control @error('ref_card') is-invalid @enderror"
                                        id="ref_card" name="ref_card" placeholder="" required />
                                    @error('ref_card')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <p>Valider la carte </p>
                                <button type="submit" class="btn btn-primary mb-2 mt-6"
                                    style="height: 2rem;">Validez</button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Les details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                            class="w-4 h-4 mr-2"></i> {{ $card->email ?? 'Inconnu' }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="mic" data-lucide="mic"
                            class="lucide lucide-mic w-4 h-4 text-slate-500 ml-auto mr-2">
                            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"></path>
                            <path d="M19 10v2a7 7 0 01-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        {{ $card->phone ?? 'Inconnu' }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                        class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> Adresse : {{ $card->address ?? null }}
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                        class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> Numero carte : {{ $card->number_card ?? null }}
                </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Pays : {{ $card->country->name ?? null }} </div>
                </div>
            </div>
        </div>


    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-6">
        <div id="doc" class="tab-pane active" role="tabpanel" aria-labelledby="doc-tab">

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des documents
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Date d'envoie
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Piece d'identité
                                            </th>
                                          
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Formulaire
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Pays
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Validez par
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Date de validation
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($card->count() > 0)
                                            {{-- @foreach ($card as $item) --}}
                                            <tr class="intro-x">
                                                <td class="w-40 text-center">

                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $card->created_at ?? null }}</p>
                                                </td>

                                                <td class="w-40 text-center">
                                                    <div class="flex">
                                                        <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                            <a href="{{ $card->identity_doc ?? null }}" target="_bank">
                                                                <img alt="image" class="max-w-sm h-auto shadow-lg"
                                                                    src="{{ asset($card->identity_doc ?? 'public/dist/images/preview-8.jpg') }}"
                                                                    title="Image">
                                                            </a>

                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td class="w-40 text-center">
                                                    <div class="flex">
                                                        <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                            <a href="{{ $card->identity_form ?? null }}" target="_bank">
                                                                <img alt="image" class="max-w-sm h-auto shadow-lg"
                                                                    src="{{ asset($card->identity_form ?? 'public/dist/images/preview-8.jpg') }}"
                                                                    title="Logo">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="w-50">

                                                    <div class="text-slate-500  mt-0.5">
                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $card->country->name ?? null }} ville :
                                                            {{ $card->citie->name ?? null }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="w-40 text-center">
                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $card->validated_by ?? null }} </p>
                                                </td>
                                                <td class="w-40 text-center">
                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $card->date_created ?? null }} </p>
                                                </td>
                                                <td class="w-40">
                                                    @if ($card->status == '1')
                                                        <div class="flex cards-center justify-center text-success">

                                                            <a class="flex items-center mr-3">
                                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                Validez
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center justify-center text-danger">

                                                            <a class="flex items-center mr-3">
                                                                <i class="fas fa-lock w-4 h-4 mr-2"></i>Invalidez
                                                            </a>

                                                        </div>
                                                    @endif
                                                </td>

                                            </tr>
                                            {{-- @endforeach --}}
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le
                                                    moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->

                <!-- END: Pagination -->

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
@endpush
