<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\NotifyController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth:web']], function () {

    // Route::group(['prefix' => 'notify'], function () {
    //     Route::get('/read', [NotifyController::class, 'read'])->name('notify.read');
    // });
    Route::group(['prefix' => 'tools'], function () {
        Route::get('/browse', [ToolController::class, 'activity'])->name('tools.activity');

        Route::group(['prefix' => 'impersonate'], function () {
            Route::get('/enable/{id}', [ToolController::class, 'enableImpersonate'])->name('tools.enableImpersonate');
            Route::get('/leave', [ToolController::class, 'leaveImpersonate'])->name('tools.leaveImpersonate');
        });
    });


    Route::group(['prefix' => 'admin'], function () {
        Route::get('/browse',  [AdminController::class, 'browse'])->name('admin.browse');
        Route::get('/add',  [AdminController::class, 'add'])->name('admin.add');
        Route::get('/add_company',  [AdminController::class, 'add_company'])->name('admin.add_company');
        Route::get('/edit_company/{id}',  [AdminController::class, 'edit_company'])->name('admin.edit_company');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
        // Route::post('updated/store', [AdminController::class, 'updateProfil_admin'])->name('admin.store');
        Route::post('update_company/{id}/strore', [AdminController::class, 'update_company'])->name('admin.company.update_company');
        Route::post('/store_company', [AdminController::class, 'store_company'])->name('admin.store_company');
        Route::get('/showForUpdate', [AdminController::class, 'showForUpdate'])->name('admin.showForUpdate');
        Route::get('/showProfil/{id}', [AdminController::class, 'ShowProfil'])->name('admin.ShowProfil');
        Route::get('/read/{id}/update', [AdminController::class, 'showUpdate'])->name('admin.showUpdate');
        Route::post('/update_password/{id}',  [AdminController::class, 'update_password'])->name('admin.update_password');
        Route::post('/update_phone/{id}',  [AdminController::class, 'update_phone'])->name('admin.update_phone');
        Route::post('/update_email/{id}',  [AdminController::class, 'update_email'])->name('admin.update_email');
        Route::post('/update/{id}',  [AdminController::class, 'update'])->name('admin.update');
        Route::get('/read/{id}/delete', [AdminController::class, 'delete'])->name('admin.delete');
        Route::get('/user/{id}/activate', [AdminController::class, 'activate'])->name('admin.activate');
    });

    Route::group(['prefix' => 'features'], function () {
        Route::post('/search', [FeatureController::class, 'search'])->name('features.search');
        Route::get('/browse',  [FeatureController::class, 'browse'])->name('features.browse');
        Route::get('/add',  [FeatureController::class, 'add'])->name('features.add');
        Route::post('/store',  [FeatureController::class, 'store'])->name('features.store');
        Route::get('/read/{id}',  [FeatureController::class, 'showUpdate'])->name('features.showUpdate');
        Route::post('/update/{id}',  [FeatureController::class, 'update'])->name('features.update');
        Route::get('/read/{id}/delete',  [FeatureController::class, 'delete'])->name('features.delete');
    });


    Route::group(['prefix' => 'permissions'], function () {
        Route::post('/search',  [PermissionController::class, 'search'])->name('permissions.search');
        Route::get('/browse',  [PermissionController::class, 'browse'])->name('permissions.browse');
        Route::get('/add', [PermissionController::class, 'add'])->name('permissions.add');
        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/read/{id}/update', [PermissionController::class, 'showUpdate'])->name('permissions.showUpdate');
        Route::post('/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::get('/read/{id}/delete', [PermissionController::class, 'delete'])->name('permissions.delete');
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::get('/browse',  [PermissionController::class, 'browse_messages'])->name('messages.browse');
        Route::get('/add', [PermissionController::class, 'add_messages'])->name('messages.add');
        Route::get('/send_custumers', [PermissionController::class, 'send_custumers_messages'])->name('send_custumers_messages.add');
        Route::get('/send_companies', [PermissionController::class, 'send_companie_messages'])->name('send_custumers_messages.add');
        Route::post('/store', [PermissionController::class, 'store_messages'])->name('messages.admin.store');
        Route::post('/custumers/store', [PermissionController::class, 'store_custumers_messages'])->name('messages.admin.store.custumers');
        Route::post('/companies/store', [PermissionController::class, 'store_companies_messages'])->name('messages.admin.store.companies');
        Route::post('/store/{id}', [PermissionController::class, 'store_one_messages'])->name('messages.one.store');
    });


      Route::group(['prefix' => 'pubs'], function () {
        Route::get('/browse',  [PermissionController::class, 'browse_pubs'])->name('pubs.browse');
        Route::get('/add', [PermissionController::class, 'add_pubs'])->name('pubs.add');
        Route::get('/edit/{id}', [PermissionController::class, 'edit_pubs'])->name('pubs.edit_pubs');
        Route::post('/store', [PermissionController::class, 'store_pubs'])->name('pubs.admin.store');
        Route::post('/store/{id}', [PermissionController::class, 'store_one_pubs'])->name('pubs.one.store');

    });

    Route::group(['prefix' => 'activites'], function () {
        Route::get('/browse',  [AdminController::class, 'browse_activites'])->name('activites.browse_activites');
        Route::get('/movements',  [AdminController::class, 'browse_activites_movements'])->name('activites.browse_activites_movements');
    });




    Route::group(['prefix' => 'roles'], function () {
        Route::post('/search', [RoleController::class, 'search'])->name('roles.search');
        Route::get('/browse', [RoleController::class, 'browse'])->name('roles.browse');
        Route::get('/add', [RoleController::class, 'add'])->name('roles.add');
        Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/read/{id}/update', [RoleController::class, 'showUpdate'])->name('roles.showUpdate');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::get('/read/{id}/delete', [RoleController::class, 'delete'])->name('roles.delete');
        Route::get('/{id}/permissions', [RoleController::class, 'rolePermissions'])->name('roles.permissions');
        Route::post('/roles/{id}/sync', [RoleController::class, 'rolePermissionsSync'])->name('roles.permissions-sync');
    });

 
});
