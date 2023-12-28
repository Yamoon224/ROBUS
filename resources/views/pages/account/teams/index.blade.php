@extends('layouts.master')

@section('title', 'Mon équipe')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        @include('components.account-nav')
        <!-- profile -->
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title float-start">Mon équipe</h4>
                <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addUser">Ajouter</button>
            </div>
            <div class="card-body py-2 my-25">
                <div>
                    <div id="toolbar" class="select">
                        <select class="form-control">
                          <option value="">Export simple</option>
                          <option value="all">Exporter tout</option>
                          <option value="selected">Exporter la selection</option>
                        </select>
                    </div>
                    <table
                        id="default-table"
                        data-sort-order="desc"
                        data-sort-name="email"
                        class="table table-hover"
                        data-locale="fr-FR"
                        data-pagination="true"
                        data-toggle="table"
                        data-search="false"
                        data-show-toggle="true"
                        data-click-to-select="true"
                        data-show-export="true"
                        data-show-columns="true"
                        data-show-columns-toggle-all="true"
                        data-toolbar="#toolbar"
                        data-mobile-responsive="true"
                        data-show-print="true"
                        data-loading-template="loadingTemplate"
                        data-show-pagination-switch="true"
                        data-show-button-icons="true">
                        <thead>
                        <tr>
                            <th data-print-ignore="true"></th>
                            <th data-field="lastname" data-sortable="true">Prénom(s)</th>
                            <th data-field="firstname" data-sortable="true">Nom</th>
                            <th data-field="email" data-sortable="true">Email</th>
                            <th data-field="Group" data-sortable="true">Rôle</th>
                            <th data-print-ignore="true">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td></td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->get_role() as $role)
                                    <span class="badge bg-info"> {{ $role->name }} </span>
                                @endforeach
                            </td>
                            <td>
                                <a class="text-danger" href="{{ route('teams.delete', ['id'=>$user->id]) }}" data-message="Etes vous sûr de vouloir effectuer cette action ?" onclick="promptAlert(this)">
                                    <i data-feather="x-circle"></i>
                                </a>
                            </td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>

@endsection

@push('modals')
<div class="modal fade" id="addUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Ajouter un utilisateur</h1>
                    <p>Cet utilisateur aura accès a votre compte</p>
                </div>
                <form class="row gy-1 pt-75" method="POST" action="{{ route('teams.add') }}">
                    @csrf
                    <div class="col-md-6 mb-1">
                        <label for="lastname" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" required name="lastname" value="{{ old('lastname') }}" aria-describedby="lastname"/>
                        @error('lastname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-1">
                        <label for="firstname" class="form-label">Prénom(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" required name="firstname" value="{{ old('firstname') }}" aria-describedby="firstname"/>
                        @error('firstname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-1">
                        <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" required name="email" value="{{ old('email') }}" aria-describedby="email"/>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-1">
                        <label class="control-label">Rôle</label>
                        <select name="roles[]" class="form-control" multiple="multiple">
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Mot de passe <span class="text-danger">*</span></label>
                        </div>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input id="password" type="password" class="form-control  form-control-merge @error('password') is-invalid @enderror" aria-describedby="password" name="password" required autocomplete="current-password">
                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="reset" class="btn btn-clear" data-bs-dismiss="modal" aria-label="Close">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-primary ms-1">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
</script>
@endpush
