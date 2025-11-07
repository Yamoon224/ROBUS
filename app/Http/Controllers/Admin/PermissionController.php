<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sid\Feature;
use Illuminate\Http\Request;
use App\Models\Sid\Permissions;
use App\Http\Controllers\Controller;
use App\Models\MessageTransaction;
use App\Models\Notification;
use App\Models\Pub;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public function browse_messages()
    {

        return view('pages.admin.browse_messages');
    }

    public function browse_pubs()
    {

        return view('pages.admin.browse_pubs');
    }


    public function add_messages()
    {

        if (!Auth::user()->hasPermissionTo('envoie-messages')) {
            abort(403);
        }
        return view('pages.admin.add_messages');
    }

    public function add_pubs()
    {

        if (!Auth::user()->hasPermissionTo('publicite')) {
            abort(403);
        }
        return view('pages.admin.add_pubs');
    }


    public function search(Request $request)
    {
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
        $permissions = Permissions::where('name', 'like', '%' . $request->get('query') . '%')->orWhere('description',  'like', '%' . $request->get('query') . '%')->paginate(10);
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

    public function store_one_messages($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('application/messages/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Données saisie incorrecte')
                ->withInput();
        }

        try {

            $user = SidUser::whereId($id)->first();
            $typ_user = $user->where('is_under_distributer', true) ? "under" : "custumers";
            $notificationId = uniqid('notif_', true);
            $messageTransaction = new MessageTransaction();
            $messageTransaction->sid_user_id = $user->id;
            $messageTransaction->title = $request->title;
            $messageTransaction->type_send = $typ_user;
            $messageTransaction->messages = $request->message;
            $messageTransaction->save();
            $notification = new Notification();
            $notification->id = $notificationId;
            $notification->notifiable_id = $user->id;
            $notification->data = json_encode([
                'title' => $request->title,
                'message' => $request->message
            ]);
            $notification->read_at = null;
            $notification->save();
            DB::commit();
            return back()->with('status', 'Les messages ont été envoyés avec succès');
        } catch (\Throwable $th) {
            return redirect('application/permissions/read/' . $id . '/update')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue')
                ->withInput();
        }
    }

    public function store_pubs(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('color', 'danger')
                ->with('status', 'Veuillez corriger les erreurs avant de continuer.');
        }

        // ✅ Traitement de l'image
        $file_front = $request->file('image');

        if ($file_front) {
            $userName = Str::slug(Auth::user()->firstname); // Slug pour éviter caractères spéciaux
            $destination_path = public_path("pubs/{$userName}/");

            // Crée le dossier s'il n'existe pas
            if (!File::exists($destination_path)) {
                File::makeDirectory($destination_path, 0755, true);
            }

            $front_file = date('YmdHis') . '.' . $file_front->getClientOriginalExtension();
            $file_front->move($destination_path, $front_file);

            // ✅ URL publique (sans "public/")
            $imageUrl = url("pubs/{$userName}/{$front_file}");

            // ✅ Enregistrement
             Pub::create([
                'title' => $request->title,
                'images' => $imageUrl,
                'messages' => $request->message,
            ]);

            return redirect()
                ->route('pubs.browse')
                ->with('color', 'success')
                ->with('status', 'La publicité a été enregistrée avec succès.');
        }

        return back()
            ->with('color', 'danger')
            ->with('status', 'Aucune image n’a été téléchargée.');
    }


    public function store_messages(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required',
            'typ_user' => 'required|string|in:under,custumers',
        ]);

        if ($validator->fails()) {
            return redirect('application/messages/add')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Données saisie incorrecte')
                ->withInput();
        }

        if ($request->typ_user === "under") {
            $users = SidUser::where('is_under_distributer', true)->get();
        } elseif ($request->typ_user === "custumers") {
            $users = SidUser::where('is_custumers', true)->get();
        } else {
            return redirect('application/messages/add')
                ->with('color', 'danger')
                ->with('status', 'Type d’utilisateur invalide')
                ->withInput();
        }



        foreach ($users as $user) {

            $notificationId = uniqid('notif_', true);
            $messageTransaction = new MessageTransaction();
            $messageTransaction->sid_user_id = $user->id;
            $messageTransaction->title = $request->title;
            $messageTransaction->type_send = $request->typ_user;
            $messageTransaction->messages = $request->message;
            $messageTransaction->save();
            $notification = new Notification();
            $notification->id = $notificationId;
            $notification->notifiable_id = $user->id;
            $notification->data = json_encode([
                'title' => $request->title,
                'message' => $request->message
            ]);
            $notification->read_at = null;
            $notification->save();
        }

        DB::commit();
        return redirect(route('messages.browse'))->with('status', 'Les messages ont été envoyés avec succès');
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
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => 'web',
            'feature_id' => $request->feature_id,
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
            return redirect('application/permissions/read/' . $id . '/update')
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
            return redirect('application/permissions/read/' . $id . '/update')
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
        try {
            DB::beginTransaction();
            $permission = Permissions::find($id);
            $permission->delete();
            DB::commit();
            return redirect(route('permissions.browse'))
                ->with('status', 'Permission supprimée avec succèss');
        } catch (\Exception $exception) {
            return redirect(route('permissions.browse'))
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue');
        }
    }
}
