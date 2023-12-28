@extends('layouts.master')

@section('title', 'Notifications')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10 mb-6">
        Liste des notifiations
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        @foreach ($notifications as $notif)
            <!-- BEGIN: Users Layout -->
            <div class="intro-y col-span-12 md:col-span-6 lg:col-span-4">
                <div class="box">
                    <div class="flex items-start px-5 pt-5">
                        <div class="w-full flex flex-col lg:flex-row items-center">
                            <div class="lg:ml-4 text-center lg:text-left mt-3 lg:mt-0">
                                <a href="{{ route('admin.make.make_read_single', $notif->id) }}"
                                    class="font-medium">{{ $notif->data['title'] ?? null }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start px-5 pt-5">
                        <div class="w-full flex flex-col lg:flex-row items-center">
                            <div class="w-16 h-16 image-fit">
                                <img alt="Rubick Tailwind HTML Admin Template" class="rounded-full"
                                    src="{{ asset('public/dist/images/notif.png') }}">
                            </div>
                            <div class="lg:ml-4 text-center lg:text-left mt-3 lg:mt-0">
                                <a href="#" class="font-medium">{{ $notif->created_at ?? null }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center lg:text-left p-5">
                        <div>{{ $notif->data['message'] ?? null }}</div>
                    </div>
                    <div class="text-center lg:text-left p-3">
                        <div>
                            <a href="{{ route('admin.make.make_read_single', $notif->id) }}"
                                class="btn btn-primary mr-1 w-23 mb-2 btn-sm" title="Lire">
                                Lire
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                {{ $notifications->links() }}
            </ul>
        </nav>
    </div>
    {{-- @else
        <h3 class="text-center text-danger">Aucune notification trouvée</h3>
    @endif --}}
    <!-- BEGIN: Users Layout -->

@endsection

@push('scripts')
@endpush
