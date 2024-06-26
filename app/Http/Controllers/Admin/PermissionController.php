<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sid\Feature;
use Illuminate\Http\Request;
use App\Models\Sid\Permissions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function browse()
    {
        if (!Auth::user()->hasPermissionTo('voir-permission')) {
            abort(403);
        }
        $features = Feature::all();
        $permissions = Permissions::with('feature')->orderBy('created_at', 'desc')->paginate(13);
        return view('pages.admin.permissions.browse', compact('features', 'permissions'));
    }

    public function search(Request $request){
        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('application/permissions')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }
        $features = Feature::all();
        $permissions = Permissions::where('name', 'like', '%'. $request->get('query') .'%')->orWhere('description',  'like', '%'. $request->get('query') .'%')->paginate(10);
        return view('pages.admin.permissions.browse', compact('permissions', 'features'));
    }

    public function add()
    {
        if (!Auth::user()->hasPermissionTo('ajouter-permission')) {
            abort(403);
        }
        $features = Feature::orderBy('created_at', 'desc')->get();
        return view('pages.admin.permissions.add', compact('features'));
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sid_permissions',
            'feature_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/permissions/add')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        Permissions::create([
            'name'=> $request->name,
            'description'=>$request->description,
            'guard_name'=>'web',
            'feature_id'=>$request->feature_id,
        ]);
        return redirect(route('permissions.browse'))->with('status', 'Permission \n créee avec succès');

    }


    public function showUpdate(int $id)
    {
        if (!Auth::user()->hasPermissionTo('modifier-permission')) {
            abort(403);
        }
        $permission = Permissions::find($id);
        $features = Feature::all();
        return view('pages.admin.permissions.showUpdate', compact('permission', 'features'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'feature_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/permissions/read/'. $id . '/update')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        try {
            $permission = Permissions::find($id);
            $permission->name = $request->name;
            $permission->description = $request->description;
            $permission->feature_id = $request->feature_id;
            $permission->save();
            return redirect(route('permissions.browse'))->with('status', 'Permission mise à jour avec succès');
        } catch (\Throwable $th) {
            return redirect('application/permissions/read/'. $id . '/update')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Une erreure est survenue')
                    ->withInput();
        }
    }

    public function delete(int $id)
    {
        if (!Auth::user()->hasPermissionTo('supprimer-permission')) {
            abort(403);
        }
        try{
            DB::beginTransaction();
            $permission = Permissions::find($id);
            $permission->delete();
            DB::commit();
            return redirect(route('permissions.browse'))
                ->with('status','Permission supprimée avec succèss');
        }catch (\Exception $exception){
            return redirect(route('permissions.browse'))
            ->with('color', 'danger')
            ->with('status','Une erreure est survenue');
        }
    }
}