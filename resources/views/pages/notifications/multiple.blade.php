@extends('layouts.master')

@section('title', 'Notifications')

@section('content')

    <div class="grid grid-cols-12 gap-6 mt-20">

        <div class="intro-y col-span-12 lg:col-span-2">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
            <!-- END: Form Validation -->
        </div>

        <div class="intro-y col-span-12 lg:col-span-8">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        <a href="{{ URL::previous() }}" class="btn btn-sm">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </a>
                    </h2>
                    <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                        <label class="form-check-label ml-0" for="show-example-5"> Notifications</label>
                    </div>
                </div>
                <div class="p-5">
                    <form class="validate-form" method="POST" action="{{ route('notifications.multiple_notifis') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <span id="errorMail" style="color:green; font-weight:bolder; padding:5px">
                            @if (\Session::has('msg'))
                                {!! \Session::get('msg') !!}
                            @endif
                        </span>
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
                            <label for="message" class="form-label"> Messages <span class="text-danger">*</span> </label>
                            <textarea type="text" class="form-control @error('message') is-invalid @enderror" id="editor" name="message"
                                placeholder="" rows="10">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-5"> <i class="fa fa-bell mr-3"></i>Envoyer
                            {{ auth()->user()->firstname }}</button>
                    </form>
                    <!-- END: Validation Form -->
                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>


@endsection

@push('scripts')
@endpush
