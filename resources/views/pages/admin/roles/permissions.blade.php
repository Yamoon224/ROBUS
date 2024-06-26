@extends('layouts.master')

@section('title', 'Rôles')

@push('styles')

@endpush

@section('content')

<div class="intro-y box">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
        <h2 class="font-medium text-base mr-auto">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>
        <div class="alert alert-danger show flex items-center mb-2 clignote" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon w-6 h-6 mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            Attribuer des permissions au rôle : <div class="font-medium text-base ml-2"> {{ $role->name }} </div> 
        </div>
    </div>
</div>

<form class="row" action="{{ route('roles.permissions-sync', ['id'=>$role->id]) }}" method="post">
<div class="intro-y grid grid-cols-12 gap-6 mt-5">
    @foreach ($features as $feature)
    @csrf
    <div class="intro-y col-span-12 md:col-span-4 box">
        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 px-5 py-4">
            <div class="ml-3 mr-auto">
                <h2 class="font-medium text-base mr-auto">
                    {{ $feature->name }}
                </h2>
            </div>
        <div class="dropdown ml-3" style="position: relative;">
            <ul class="dropdown-content">
                @if (Auth::user()->hasPermissionTo('modifier-fonctionnalite'))
                <li class="nav-item">
                    <a href="{{ route('features.showUpdate', ['id'=> $feature->id]) }}" class="text-dark">
                        <i data-feather="check-square" class="dropdown-toggle w-5 h-5 text-slate-500"></i>
                    </a>
                </li>
               @endif
            </ul>
        </div>   
       </div>
        <div  class="p-5">
            @foreach($feature->permissions as $permission)
                 <div class="switchery-list m-1">
                    <input
                        name="permissions[]"
                        value="{{ $permission->id }}" type="checkbox" @if($role->permissions->contains('id', $permission->id)) checked="checked" @endif
                        class="sid-switch" id="customCheck-{{ $permission->id }}">
                    <label class="custom-control-label sid-switch-label" for="customCheck-{{ $permission->id }}">{{ $permission->name }}</label>
                </div>
             @endforeach
        </div>
    </div>
    @endforeach
</div>
<div class="col-md-12 text-right">
    <a href="{{ route('roles.browse') }}" class="btn btn-alert ml-3 mt-3">Annuler & Retourner </a>
    <input type="submit" value="Enregistrer" class="btn btn-primary ml-3 mt-3">
</div>
</form>
@endsection

@push('scripts')
<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.sid-switch'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html, { size: 'small' });
    });
</script>
@endpush
