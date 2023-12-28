<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Tools\Image;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{

    public function browse(Request $request)
    {
        if (Auth::user()->hasPermissionTo('voir-marques')) {
            
            $pages = Page::orderBy('created_at', 'desc')->get();
            
            return view('pages.admin.pages.browse', compact('pages'));
        } else {
            abort(403);
        }
    }
    public function add()
    {
        if (Auth::user()->hasPermissionTo('ajouter-categorie')) {
            return view('pages.admin.pages.add');
        } else {
            abort(403);
        }

    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
          $upload = new Upload;
            $upload->file_original_name = null;

            $arr = explode('.', $request->file('upload')->getClientOriginalName());

            for($i=0; $i < count($arr)-1; $i++){
                if($i == 0){
                    $upload->file_original_name .= $arr[$i];
                }
                else{
                    $upload->file_original_name .= ".".$arr[$i];
                }
            }
            
            $filenameWithExt = $request->file('upload')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $filenameWithTime = $filename.'_'.time();
            $fileNameToStore = $filenameWithTime . '.' .$extension;
            $upload->file_name = $request->file('upload')->storeAs('public/' . Auth::user()->company_id, $fileNameToStore);;
            $upload->user_id = Auth::user()->id;
            $upload->extension = $request->file('upload')->getClientOriginalExtension();
            if(isset($type[$upload->extension])){
                $upload->type = $type[$upload->extension];
            }
            else{
                $upload->type = "others";
            }
            $upload->file_size = $request->file('upload')->getSize();
            $upload->save();

            return '{}';
        }
    }

    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'content' => 'required',
            'title' => 'required',
            'meta_description' => 'required',
         ]);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        Page::create([
            'title'=> $request->title,
            'content'=>  $request->content,
            'meta_description'=>  $request->meta_description ,
            'slug'=>'About-us',
        ]);

        return redirect(route('pages.browse'))->with('status', 'About créee avec succès');

    }

    public function show($slug)
    {
        if (!Auth::user()->hasPermissionTo('modifier-categorie')) {
            abort(403);
        }
        // $page  = Page::find($slug);
        $page = Page::where('slug', $slug)->first();
        return view('pages.admin.pages.showUpdate', compact('page'));
    }

    public function update(Request $request, int $id)
    {
        
       $validator = Validator::make($request->all(), [
            'content' => 'required',
            'title' => 'required',
            'meta_description' => 'required',
        ]);


        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Données saisie incorrecte')
                    ->withInput();
        }

        try {
                    
            $page = Page::findOrFail($id);
            $page->title = $request->title;
            $page->content = $request->content;
            $page->slug = 'About-us';
            $page->meta_description = $request->meta_description ;

            $page->save();
            
            return redirect(route('pages.browse'))->with('status', 'A propos mise à jour avec succès');
        } catch (\Throwable $th) {
            return  back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Une erreure est survenue')
                    ->withInput();
        }
    } 
}