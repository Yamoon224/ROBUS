<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\AuthenticationService;
use App\Repositories\RegisterRepository;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use App\Models\Wallets\Wallet;
use Illuminate\Http\Request;
use App\Models\IntegrationClient;
use Illuminate\Support\Facades\Validator;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use App\Models\Custumers\City;

class IntegrationController extends Controller
{


    /** @var  RegisterRepository */

    private $registerRepository;


    public function __construct(
        RegisterRepository $registerReposi,

    ) {
        $this->registerRepository = $registerReposi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function prospect_registers()
    {


        $countries = Country::where('is_web', false)
            ->whereNotNull('indecative')
            ->where('is_integrate', true)
            ->orderBy('name', 'asc')
            ->get();


        return view('pages.integrations.browse_registers', [
            'countries' => $countries,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function searchCountrieName($countryId)
    {
        return Country::whereId($countryId)->select('indecative')->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Respo
     */

    public function prospect_store(Request $request)
    {

        try {


            $validator = Validator::make(

                $request->all(),
                [
                    'name_firm' => 'required',
                    'legal_form' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|email|unique:sid_users,email',
                    'country_id' => 'required',
                    'terms' => 'required|accepted',
                    'phone' => 'required',
                    'password' => 'required',
                    'sector_of_activity' => 'required',
                ],
                [

                    'name_firm.required' => 'Le nom de l\'entreprise est obligatoire',
                    'legal_form.required' => 'La forme juridique est obligatoire',
                    'firstname.required' => 'Le prénom est obligatoire',
                    'lastname.required' => 'Le nom est obligatoire',
                    'email.required' => 'L\'email est obligatoire',
                    'terms.required' => 'Vous devez accepter les conditions générales et la politique de confidentialité',
                    'terms.accepted' => 'Vous devez accepter les conditions générales et la politique de confidentialité',
                    'email.unique' => 'Cet email est déjà utilisé',
                    'country_id.required' => 'Le pays est obligatoire',
                    'phone.required' => 'Le numéro de téléphone est obligatoire',
                    'password.required' => 'Le mot de passe est obligatoire',
                    'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
                    'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
                    'sector_of_activity' => 'Le secteur d\'activité doit etre obligatoire',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Veuillez corriger les erreurs')
                    ->withInput();
            }


            $country = Country::find($request->country_id);
            if (!$country) {
                return back()
                    ->with('color', 'danger')
                    ->with('status', 'Pays non trouvé')
                    ->withInput();
            }

            $phone_complet = $country->indicatif . $request->phone;

            $existingUser = $this->registerRepository->where('phone', $phone_complet)
                ->orWhere('email', $request->email)
                ->withTrashed()
                ->first();

            $existingName = IntegrationClient::where('name_firm', $request->name_firm)
                ->withTrashed()
                ->first();

            if ($existingName) {
                return back()
                    ->with('color', 'danger')
                    ->with('status', 'Le nom de l\'entreprise existe déjà')
                    ->withInput();
            }

            if ($existingUser) {
                return back()
                    ->with('color', 'danger')
                    ->with('status', 'Un utilisateur avec cet email ou numéro de téléphone existe déjà')
                    ->withInput();
            }



            $rt = rand(40, 5000) * time();
            $tr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $walletCode = sprintf("%0.6s", str_shuffle($rt . $tr));

            $user = $this->registerRepository->create([
                'country_id'  => $country->id,
                'company_id'  => 1,
                'phone'  => $phone_complet,
                'email'  => $request->email,
                'firstname'  => $request->firstname,
                'lastname'  => $request->lastname,
                'name_ground'  => $request->name_firm,
                'zone'  => $request->zone ?? null,
                'is_api' => true,
                'status' => true,
                'created_by' => 1,
                'is_custumers' => false,
                'is_active' => true,
                'password' => bcrypt($request->password),
                'verification_code' => null,
                'is_accepted' => $request->terms,
                'activation_code' => null
            ]);

            $wallet_user = Wallet::create([
                'company_id' => 1,
                'wallet_id' => $country->code . $walletCode,
                'country_id' => $country->id,
                'created_by' => $user->id,
                'sid_user_id' => $user->id,
                'currencie_id' => $country->currencie->id ?? 1,
                'amount' => 0.0,
                'reload_by' => $user->firstname . ' ' . $user->lastname,
                'status' => true,
            ]);

            IntegrationClient::create([
                'sid_user_id' => $user->id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
                'legal_form'  => $request->legal_form,
                'name_firm'  => $request->name_firm,
                'site_url' => $request->site_url,
                'sector_of_activity' => $request->sector_of_activity,
                'is_active' => false,
            ]);

            try {

                $originalLogoPath = public_path('dist/images/logo_violet.jpeg');

                $qrData = json_encode([
                    'wallet_id' => $wallet_user->wallet_id,
                    'user' => $user->firstname . ' ' . $user->lastname,
                    'countrie' => $country->name,
                    'currencie' => $country->currencie->name ?? 'XOF',
                ]);

                $result = Builder::create()
                    ->data($qrData)
                    ->encoding(new Encoding('UTF-8'))
                    ->size(300)
                    ->margin(10)
                    ->logoPath($originalLogoPath)
                    ->logoResizeToWidth(70)
                    ->logoResizeToHeight(70)
                    ->build();

                $qrPath = 'qrcodes/wallet_' . $wallet_user->id . '.png';
                $result->saveToFile(public_path($qrPath));

                $wallet_user->update(['qr_code' => $qrPath]);
            } catch (\Throwable $th) {
                // Continuer même si le QR code échoue
            }

            return redirect()->route('admin.login_integration')
                ->with('color', 'success')
                ->with('status', 'Compte créé avec succès! Un commercial vous contactera sous peu.');
        } catch (\Exception $exception) {

            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de la demande')
                ->withInput();
        }
    }
}
