<?php

namespace App\Http\Controllers;

use App\Tools\Image;
use Illuminate\Support\Str;
use App\Models\ProviderType;
use Illuminate\Http\Request;
use App\Models\CountryService;
use App\Models\CountryWeb;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse(Request $request)
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-operateurs')) {
            abort(403);
        }

        try {
            return response(view('pages.services.browse')->render());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('services.browse : ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file'      => $e->getFile() . ':' . $e->getLine(),
            ]);

            // Diagnostic : /services/browse?diag=1 affiche l'erreur (réservé aux utilisateurs autorisés ci-dessus).
            if ($request->boolean('diag')) {
                return response(
                    '<pre style="white-space:pre-wrap;padding:20px">'
                    . e(get_class($e) . ': ' . $e->getMessage() . "
" . $e->getFile() . ':' . $e->getLine())
                    . '</pre>',
                    500
                );
            }

            throw $e;
        }
    }

    public function browse_pixel_innove()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-operateurs')) {
            abort(403);
        }

        return view('pages.services.pixel_innove', ['mode' => 'mobile_money']);
    }

    public function browse_pixel_innove_uv()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-operateurs')) {
            abort(403);
        }

        return view('pages.services.pixel_innove', ['mode' => 'uv_robus']);
    }

    public function  browse_visitores()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-operateurs')) {
            abort(403);
        }

        return view('pages.services.browse_visitores');
    }

    public function browse_close_app()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('close-robus')) {
            abort(403);
        }

        return view('pages.services.browse_close_app');
    }

    public function browse_close_app_masters()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('close-robus')) {
            abort(403);
        }

        return view('pages.services.browse_close_app_master');
    }

    public function browse_close_app_comzones()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('close-robus')) {
            abort(403);
        }

        return view('pages.services.browse_close_app_comzone');
    }

    public function browse_close_app_visitors()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('close-robus')) {
            abort(403);
        }

        return view('pages.services.browse_close_app_visitors');
    }

    public function browse_close_app_companies()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('close-robus')) {
            abort(403);
        }

        return view('pages.services.browse_close_app_companies');
    }

    public function browse_operators()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-operateurs')) {
            abort(403);
        }

        return view('pages.services.browse_operators');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_offers()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-operateurs')) {
            abort(403);
        }

        return view('pages.services.browse_offers');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('ajouter-operateurs')) {
            abort(403);
        }
        $countries = Country::where('robus', 1)
            ->get();
        $providerTypes = ProviderType::where('status', true)->get();

        return view('pages.services.add', compact('countries', 'providerTypes'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->hasPermissionTo('modifier-operateur')) {

            $provider = Provider::where('id', $id)->first();
            $countries = Country::where('robus', 1)
                ->get();
            $providerTypes = ProviderType::where('status', true)->get();

            if (empty($provider)) {
                return redirect(route('services.browse'))->with('color', 'danger')->with('status', 'Service introuvable');
            }
            return view('pages.services.edit', compact('countries', 'provider', 'providerTypes'));
        } else {
            abort(403);
        }
    }

    public function edit_visitors($id)
    {
        if (Auth::user()->hasPermissionTo('modifier-operateur')) {

            $provider = Provider::where('id', $id)->first();
            $countries = Country::whereIsWeb(true)
                ->get();
            $providerTypes = ProviderType::where('status', true)->get();

            if (empty($provider)) {
                return redirect(route('services.browse_visitores'))->with('color', 'danger')->with('status', 'Service introuvable');
            }
            return view('pages.services.edit_visitors', compact('countries', 'provider', 'providerTypes'));
        } else {
            abort(403);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Veuillez renseigner tous les champ requis !')
                ->withInput();
        }

        try {

            $provider = new Provider();
            $provider->name = $request->name;
            $provider->description  = $request->description;
            $provider->company_id = Auth::guard('web')->user()->company_id ?? null;
            $provider->created_by = Auth::user()->id;
            $provider->status = true;
            $provider->provider_type_id = $request->provider_type_id;

            // if ($request->image) {
            //     $tool = new Image;
            //     $provider->image = $tool->saveInDisk($request, 'services/' . Auth::user()->company_id . '/services');
            // }


            // $provider->country_services()->delete();
            // $kits-> ()->delete();

            if ($provider->save()) {

                foreach ($request->country_id as $key => $country_s_id) {

                    $country_service = new CountryService;
                    $country_service->provider_id = $provider->id;
                    $country_service->country_id = $country_s_id;
                    $root_countrie = Country::findOrFail($country_s_id);

                    $country_service->save();

                    $root_countrie->save();
                }

                $root_countrie->save();

                return redirect(route('services.browse'))->with('status', 'Service ajouter avec succès');
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue')
                ->withInput();
        }
    }

    /** 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, int $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Veuillez renseigner tous les champ requis !')
                ->withInput();
        }

        try {

            $provider = Provider::findOrFail($id);

            // Nouveau logo : stocké dans dist/providers (même emplacement que les logos existants)
            if ($request->hasFile('image')) {
                $file     = $request->file('image');
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('dist/providers'), $fileName);
                $provider->image = 'dist/providers/' . $fileName;
            }
            $provider->name = $request->name;
            $provider->description  = $request->description;
            $provider->company_id = Auth::guard('web')->user()->company_id ?? null;
            $provider->created_by = Auth::user()->id;
            $provider->status = true;
            $provider->amount = $request->amount ?? $provider->amount;
            $provider->percent_merchands = $request->percent_merchands ?? $provider->percent_merchands;
            $provider->provider_type_id = $request->provider_type_id;

            // if ($request->image) {
            //     $tool = new Image;
            //     $provider->image = $tool->saveInDisk($request, 'services/' . Auth::user()->company_id . '/services');
            // }


            // Les pays ne sont resynchronisés que s'ils sont fournis (sinon on conserve l'existant)
            $countryIds = array_filter((array) $request->country_id);
            if (!empty($countryIds)) {
                $provider->country_services()->delete();
            }

            if ($provider->save()) {

                foreach ($countryIds as $key => $country_s_id) {

                    $country_service = new CountryService;
                    $country_service->provider_id = $provider->id;
                    $country_service->country_id = $country_s_id;
                    $root_countrie = Country::findOrFail($country_s_id);

                    $country_service->save();

                    $root_countrie->save();
                }

                Artisan::call('cache:clear');
                return redirect(route('services.browse'))->with('status', 'Service modifier avec succès');
            }
        } catch (\Throwable $th) {
            \Log::error('Service update : ' . $th->getMessage(), ['provider_id' => $id]);
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue')
                ->withInput();
        }
    }

    public function updateWeb(Request $request, int $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Veuillez renseigner tous les champ requis !')
                ->withInput();
        }

        try {

            $provider = Provider::findOrFail($id);
            $provider->name = $request->name;
            $provider->description  = $request->description;
            $provider->company_id = Auth::guard('web')->user()->company_id ?? null;
            $provider->created_by = Auth::user()->id;
            $provider->status = true;
            $provider->amount = $request->amount ?? $provider->amount;
            $provider->percent_merchands = $request->percent_merchands ?? $provider->percent_merchands;
            $provider->provider_type_id = $request->provider_type_id;

            // if ($request->image) {
            //     $tool = new Image;
            //     $provider->image = $tool->saveInDisk($request, 'services/' . Auth::user()->company_id . '/services');
            // }

            $provider->country_service_web()->delete();

            if ($provider->save()) {

                foreach ($request->country_id as $key => $country_s_id) {

                    $country_service = new CountryWeb;
                    $country_service->provider_id = $provider->id;
                    $country_service->country_id = $country_s_id;
                    $root_countrie = Country::findOrFail($country_s_id);

                    $country_service->save();

                    $root_countrie->save();
                }

                $root_countrie->save();

                return redirect(route('services.browse_visitores'))->with('status', 'Service modifier avec succès');
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue')
                ->withInput();
        }
    }
}
