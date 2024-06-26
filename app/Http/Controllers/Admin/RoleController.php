<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sid\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function browse()
    {
        // if (!Auth::user()->hasPermissionTo('voir-role')) {
        //     abort(403);
        // }
        $roles = Role::orderBy('created_at', 'DESC')->paginate(10);
        return view('pages.admin.roles.browse', compact('roles'));
    }

    public function search(Request $request){
        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('application/roles')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }
        $roles = Role::where('name', 'like', '%'. $request->get('query') .'%')->orWhere('description',  'like', '%'. $request->get('query') .'%')->paginate(10);
        return view('pages.admin.roles.browse', compact('roles'));
    }

    public function add()
    {
        // if (!Auth::user()->hasPermissionTo('ajouter-role')) {
        //     abort(403);
        // }
        $roles = Role::orderBy('created_at', 'DESC')->get();
        return view('pages.admin.roles.add', compact('roles'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sid_roles',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/roles/add')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        Role::create([
            'name'=> $request->name,
            'description'=>  $request->description,
            'guard_name'=>'web',
        ]);

        return redirect(route('roles.browse'))->with('status', 'Role crée avec succès');

    }

    public function showUpdate(int $id)
    {
        // if (!Auth::user()->hasPermissionTo('modifier-role')) {
        //     abort(403);
        // }
        $role  = Role::find($id);
        return view('pages.admin.roles.showUpdate', compact('role'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/roles/read/'. $id . '/update')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        try {
            $role = Role::find($id);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();
            return redirect(route('roles.browse'))->with('status', 'Rôle mise à jour avec succès');
        } catch (\Throwable $th) {
            return redirect('application/roles/read/'. $id . '/update')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Une erreure est survenue')
                    ->withInput();
        }
    }


    public function delete(int $id)
    {
        // if (!Auth::user()->hasPermissionTo('supprimer-role')) {
        //     abort(403);
        // }
        try{
            DB::beginTransaction();
            $role = Role::find($id);
            $role->delete();
            DB::commit();
            return redirect(route('roles.browse'))
                ->with('status','Rôle supprimé avec succèss');
        }catch (\Exception $exception){
            return redirect(route('roles.browse'))
            ->with('color', 'danger')
            ->with('status','Une erreure est survenue');
        }
    }

    public function rolePermissions(int $id)
    {
        // if (!Auth::user()->hasPermissionTo('configurer-role')) {
        //     abort(403);
        // }
        $role = Role::with('permissions')->where('id', $id)->first();
        $features = Feature::with('permissions')->get();
        return view('pages.admin.roles.permissions', compact("role", 'features'));
    }

    public function rolePermissionsSync(Request $request, int $id)
    {
        try{
            DB::beginTransaction();
            $role = Role::find($id);
            $role->revokePermissionTo($request['permissions']);
            $role->syncPermissions($request['permissions']);
            DB::commit();
            return redirect(route('roles.browse'))
                ->with('status','Rôles configurés avec succèss');
        }catch (\Exception $exception){
            return redirect(route('roles.browse'))
            ->with('color', 'danger')
            ->with('status','Une erreure est survenue');
        }
    }
}