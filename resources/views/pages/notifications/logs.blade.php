@extends('layouts.master')

@section('title', 'Liste des activites')

@section('content')
    @if (count($logs) == 0)
        <div class="grid grid-cols-12 gap-6 mt-20">
            {{-- <div class="intro-y col-span-12 lg:col-span-3">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

        </div>
        <!-- END: Form Validation -->
    </div> --}}
            <div class="col-span-12 lg:col-span-3 text-center">
                <!-- BEGIN: Form Validation -->

            </div>
            <div class="col-span-12 lg:col-span-6 text-center">
                <!-- BEGIN: Form Validation -->
                <div class="text-center mt-5">

                    <h4 class="mt-1">Pas de notifications</h4>
                    <p>
                        Vous n'avez pas encore logs.
                        <br>
                    </p>

                </div>
                <!-- END: Form Validation -->
            </div>
            <div class="col-span-12 lg:col-span-3 text-center">
                <!-- BEGIN: Form Validation -->


                <!-- END: Form Validation -->
            </div>
        </div>
    @else
        <h2 class="intro-y text-lg font-medium mt-10 ml-l00">
            Liste des activites des secretaires
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table id="table_id" class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap"># </th>
                            <th class="text-center whitespace-nowrap">Utilisateurs</th>
                            <th class="text-center whitespace-nowrap">Nom & prenom</th>
                            <th class="text-center whitespace-nowrap">Email</th>
                            <th class="text-center whitespace-nowrap">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $key => $log)
                            <tr class="intro-x">
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if ($log->sidUser != null)
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                            <p class="font-medium whitespace-nowrap"> {{ $log->sidUser->firstname }}</p>
                                        </div>
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $log->sidUser->zone->name ?? null }}
                                            </span>
                                        </div>
                                    @else
                                        Invitée ({{ $log->guest_id }})
                                    @endif
                                </td>
                                <td>
                                    @if ($log->user != null)
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                            <p class="font-medium whitespace-nowrap"> {{ $log->user->firstname }}</p>
                                        </div>
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $log->user->zone->name ?? null }}
                                            </span>
                                        </div>
                                    @else
                                        Invitée ({{ $log->guest_id }})
                                    @endif
                                </td>
                                <td>
                                    @if ($log->user != null)
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $log->user->email ?? null }}
                                            </span>
                                        </div>
                                    @else
                                        Invitée ({{ $log->guest_id }})
                                    @endif
                                </td>
                                <td>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                        <p class="font-medium whitespace-nowrap"> {{ $log->log }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- END: Data List -->

        </div>

    @endif
@endsection

@push('scripts')
    <!-- the main fileinput plugin script JS file -->

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
@endpush
