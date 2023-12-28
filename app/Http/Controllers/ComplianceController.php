<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\Media;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComplianceController extends Controller
{
    public function index(){

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-entreprise')) {
            abort(403);
        }
        $company = Company::find(Auth::user()->company_id);
        $countries = Country::where('robus', true)->orderBy('name', 'ASC')->get();
        $legals = array('SAS', 'SA', 'SARL');
        $sectors = array('Finances', 'Commerce', 'Digital');
        $currencies = Currency::where('status', true)->orderBy('code', 'asc')->get();
        return view('pages.account.compliance.index', compact('company', 'countries', 'legals', 'sectors', 'currencies'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'legal' => ['required', 'string', 'max:255'],
            'sector' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'string', 'max:255'],
            'full_phone' => ['required', 'string', 'max:255'],
        ]);


        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de modification')
                    ->withInput();
        }
        try{
            $item = Company::find(Auth::user()->company_id);
            $item->name = $request->name;
            $item->legal = $request->legal;
            $item->sector = $request->sector;
            $item->description = $request->description;
            $item->website = $request->website;
            $item->phone = $request->full_phone;
            $item->country_id = $request->country_id;
            $item->city = $request->city;
            $item->address = $request->address;
            $item->currency_id = $request->currency_id;
            $item->save();

            DB::commit();
            return redirect(route('compliance'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception){
            return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de modification')
                    ->withInput();
        }
    }

   
}