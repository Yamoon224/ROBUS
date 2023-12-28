<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sid\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FeatureController extends Controller
{

    public function browse()
    {
        if (Auth::user()->hasPermissionTo('voir-fonctionnalite')) {
            $features = Feature::orderBy('created_at','desc')->paginate(13);
            return view('pages.admin.features.browse', compact('features'));
        } else {
            abort(403);
        }
    }

    public function search(Request $request){
        $validator = Validator::make($request->all(), [
            'query' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/features')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }
        $features = Feature::where('name', 'like', '%'. $request->get('query') .'%')->orWhere('description',  'like', '%'. $request->get('query') .'%')->paginate(10);
        return view('pages.admin.features.browse', compact('features'));
    }

    public function add()
    {
        if (Auth::user()->hasPermissionTo('ajouter-fonctionnalite')) {
            return view('pages.admin.features.add');
        } else {
            abort(403);
        }

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sid_features',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/features/add')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        Feature::create([
            'name'=> $request->name,
            'description'=>  $request->description,
        ]);

        return redirect(route('features.browse'))->with('status', 'Fonctionnalité créee avec succès');

    }

    public function showUpdate(int $id)
    {
        if (!Auth::user()->hasPermissionTo('modifier-fonctionnalite')) {
            abort(403);
        }
        $features  = Feature::find($id);
        return view('pages.admin.features.showUpdate', compact('features'));
    }


    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/features/read/'. $id . '/update')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        try {
            $feature = Feature::findOrFail($id);
            $feature->name = $request->name;
            $feature->description = $request->description;
            $feature->save();
            return redirect(route('features.browse'))->with('status', 'Fonctionnalité mise à jour avec succès');
        } catch (\Throwable $th) {
            return redirect('application/features/read/'. $id . '/update')
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Une erreure est survenue')
                    ->withInput();
        }
    }

    public function delete(int $id)
    {
        if (!Auth::user()->hasPermissionTo('supprimer-fonctionnalite')) {
            abort(403);
        }
        try{
            DB::beginTransaction();
            $feature = Feature::findOrFail($id);
            $feature->delete();
            DB::commit();
            return redirect(route('features.browse'))
                ->with('status','Fonctionnalité supprimée avec succèss');
        }catch (\Exception $exception){
            return redirect(route('features.browse'))
            ->with('color', 'danger')
            ->with('status','Une erreure est survenue');
        }
    }
}
