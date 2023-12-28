<?php

namespace App\Http\Controllers;

use App\Tools\Image;
use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Models\Contacts\Contact;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Contacts\ContactList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Redirect\RedirectType;

class ContactController extends Controller
{
    //  private function getOperationsByContact(int $contact_id) {
    //     $operations = Transfers::where('contact_id', $contact_id)->where('status', '!=', TransferEnum::CANCELLED)->where('status', '!=', TransferEnum::ERROR)->get();
    //     return $operations;
    // }

    private function getContactById(int $id) {
        $contact = Contact::where('company_id', Auth::user()->company_id)->where('id', $id)->first();
        return $contact;
    }

    public function add($key)
    {
        $list_contact = ContactList::where('company_id', Auth::user()->company_id)->where('id', $key)->first();
        $countries = Country::where('status', true)->orderBy('name', 'asc')->get();
        $user = SidUser::where('id', Auth::user()->id)->first();
        return view('pages.contacts.add', compact('list_contact', 'countries', 'user'));
    }

    // public function import($key)
    // {
    //     $list_contact = ContactList::where('company_id', Auth::user()->company_id)->where('id', $key)->first();
    //     //$countries = Country::where('status', true)->orderBy('name', 'ASC')->get();
    //     //$user = User::where('id', Auth::user()->id)->first();
    //     $template_demo_url = Storage::url('contacts/demo/contact_demo.csv');
    //     return view('pages.contacts.import', compact('list_contact', 'template_demo_url'));
    // }

    // public function register(Request $request, $key)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'contacts_file' => ['required', 'file', 'mimes:csv,xlsx'],
    //     ]);

    //     if ($validator->fails()) {
    //         return RedirectType::REDIRECT_URI('contacts.add', ['key' => $key], "Erreur d'importation");
    //     }

    //     $file = $request->file('contacts_file');

    //     $import = new ContactImport($key);

    //     $import->import($file);

    //     if ($import->errors()->isNotEmpty()) {
    //         return RedirectType::REDIRECT_URI('contacts.import', ['key' => $key], "Le fichier n'a pas pu être importé");
    //     } elseif ($import->failures()->isNotEmpty()) {
    //         return RedirectType::REDIRECT_URI('contacts.list.show', ['key' => $key], 'Une ou plusieurs lignes ont été ignorées dans cette importation', 'warning');
    //     }
    //     $nbr_items = 0;
    //     $contacts = Contact::where('company_id', Auth::user()->company_id)->get();

    //     foreach ($import->getContacts() as $contact) {
    //         if (!empty($contact->phone) || !empty($contact->email)) {
    //             if (empty(Contact::where('company_id', Auth::user()->company_id)->where('phone', $contact->phone)->first()) || empty(Contact::where('company_id', Auth::user()->company_id)->where('email', $contact->email)->first())) {
    //                 $nbr_items++;
    //                 $contact->save();
    //             }
    //         }
    //     }

    //     $path = $file->storeAs(
    //         'public/contacts/files/company-' . Auth::user()->company_id . '/' . Carbon::now()->format('Y') . '/' . Carbon::now()->format('m'),
    //         Carbon::now()->format('d') . '-contact' . uniqid() . 'user-' . Auth::user()->id . '.' . $file->getClientOriginalExtension()
    //     );

    //     return RedirectType::REDIRECT_URI('contacts.list.show', ['key' => $key], $nbr_items . '/' . $import->getTotalItems() . ' contacts ont été importé', 'success');
    // }

    public function show($key)
    {
        $table = true;
        $item = $this->getContactById($key);
        if (empty($item)) {
            return RedirectType::REDIRECT_URI('contacts.list.browse');
        }
        $country = Country::where('id', $item->country_id)->first();
        $list_contact = ContactList::where('company_id', Auth::user()->company_id)->where('id', $item->contact_list_id)->first();
        $operations = $this->getOperationsByContact($key);
        return view('pages.contacts.show', compact('item', 'country', 'list_contact', 'operations', 'table'));
    }

    public function edit($key, $id)
    {

        $item = Contact::where('company_id', Auth::user()->company_id)->where('id', $key)->where('contact_list_id', $id)->first();
        $countries = Country::where('status', true)->orderBy('name', 'ASC')->get();
        $user = SidUser::where('id', Auth::user()->id)->first();
        if (empty($item)) {
            return RedirectType::REDIRECT_URI('contacts.list.browse');
        }
        $operations = count($this->getOperationsByContact($key));
        return view('pages.contacts.edit', compact('item', 'countries', 'user', 'operations'));
    }

    public function store(Request $request, $key)
    {
        $validator = Validator::make($request->all(), [
            'phone'     => ['nullable', 'string', 'max:20', 'min:8'],
            'email'     => ['nullable', 'email'],
        ]);

        if ($validator->fails()) {
            return RedirectType::REDIRECT_URI('contacts.add', ['key' => $key], RedirectType::DEFAULT_VALIDATION_MESSAGE);
        } elseif (!$request->phone and !$request->email) {
            return RedirectType::REDIRECT_BACK("Vous devez renseigner un numéro de téléphone ou une adresse email");
        }
        try {

            if (!empty(Contact::where('company_id', Auth::user()->company_id)->where('email', $request->email)->first()) && !empty($request->email)) {
                return RedirectType::REDIRECT_URI('contacts.add', ['key' => $key], "Cette adresse email existe déjà ($request->email)");
            } elseif (!empty(Contact::where('company_id', Auth::user()->company_id)->where('phone', $request->phone)->first())) {
                return RedirectType::REDIRECT_URI('contacts.add', ['key' => $key], "Ce numéro de téléphone existe déjà ($request->phone)");
            }

            $item = new Contact();
            $tool = new Image;
            $item->image = $tool->saveInDisk($request, 'contacts/' . Auth::user()->company_id);
            $item->firstname = $request->firstname;
            $item->lastname = $request->lastname;
            $item->contact_list_id = $key;
            $item->phone = $request->phone;
            $item->country_id = $request->country_id;
            $item->city = $request->city;
            $item->email = $request->email;
            $item->status = $request->status ? true : false;
            $item->lot = uniqid();
            $item->company_id = Auth::user()->company_id;
            $item->created_by = Auth::user()->id;
            $item->save();
            return RedirectType::REDIRECT_URI('contacts.list.show', ['key' => $key], 'Contact crée avec succès', 'success');
        } catch (\Exception $exception) {
            return RedirectType::REDIRECT_URI('contacts.add', ['key' => $key], RedirectType::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function update(Request $request, $key, $id)
    {
        // id => List contact
        // key=> Contact
        $validator = Validator::make($request->all(), [
            'phone'     => ['nullable', 'string', 'max:20', 'min:8'],
            'email'     => ['nullable', 'email'],
        ]);
        if ($validator->fails()) {
            return RedirectType::REDIRECT_URI('contacts.edit', ['id' => $id, 'key' => $key], RedirectType::DEFAULT_VALIDATION_MESSAGE);
        }
        try {

            $item = Contact::where('company_id', Auth::user()->company_id)->where('contact_list_id', $id)->where('id', $key)->first();
            if (empty($item)) {
                return RedirectType::REDIRECT_URI('contacts.show', ['key' => $key]);
            } elseif (!$request->phone and !$request->email) {
                return RedirectType::REDIRECT_BACK("Vous devez renseigner un numéro de téléphone ou une adresse email");
            }

            if (!empty(Contact::where('company_id', Auth::user()->company_id)->where('email', $request->email)->first()) && Contact::where('company_id', Auth::user()->company_id)->where('email', $request->email)->first()->email != $item->email  && !empty($request->email)) {
                return RedirectType::REDIRECT_BACK("Cette adresse email existe déjà ($request->email)");
            } elseif (!empty(Contact::where('company_id', Auth::user()->company_id)->where('phone', $request->phone)->first()) && Contact::where('company_id', Auth::user()->company_id)->where('phone', $request->phone)->first()->phone != $item->phone && !empty($request->phone)) {
                return RedirectType::REDIRECT_BACK("Ce numéro de téléphone existe déjà ($request->phone)");
            }elseif(count($this->getOperationsByContact($key)) != 0 && isset($request->phone) ) {
                return RedirectType::REDIRECT_BACK("Vous ne pouvez pas modifier ce numéro de téléphone, il est associé à une opération");
            }

            if ($request->image) {
                $tool = new Image;
                $item->image = $tool->saveInDisk($request, 'contacts/' . Auth::user()->company_id);
            }

            $item->firstname = $request->firstname;
            $item->lastname = $request->lastname;
            if(count($this->getOperationsByContact($key)) === 0) {
                $item->phone = $request->phone;
            }
            $item->email = $request->email;
            $item->country_id = $request->country_id;
            $item->status = $request->status ? true : false;
            $item->save();
            return RedirectType::REDIRECT_URI('contacts.list.show', ['key' => $id], RedirectType::DEFAULT_EDITED_MESSAGE, 'success');
        } catch (\Exception $exception) {
            return RedirectType::REDIRECT_URI('contacts.edit', ['id' => $id, 'key' => $key], RedirectType::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function delete($key)
    {
        try {
            DB::beginTransaction();
            $item = $this->getContactById($key);
            if (empty($item)) {
                return RedirectType::REDIRECT_URI('contacts.show', ['key' => $key]);
            }
            $item->deleted_by = Auth::user()->id;
            $item->save();
            $item->delete();
            DB::commit();

            $msg = RedirectType::DEFAULT_DELETED_MESSAGE;
            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = RedirectType::DEFAULT_ERROR_MESSAGE;
            $color = 'danger';
        }
        return RedirectType::REDIRECT_BACK($msg, $color);
    }

    // public function deleteOperation($key)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $item = Transfers::where('id', $key)->first();
    //         if (empty($item)) {
    //             return RedirectType::REDIRECT_URI('contacts.show', ['key' => $key]);
    //         }
    //         $item->in_history = false;
    //         $item->save();
    //         //$item->delete();
    //         DB::commit();

    //         $msg = RedirectType::DEFAULT_DELETED_MESSAGE;
    //         $color = 'success';
    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         $msg = RedirectType::DEFAULT_ERROR_MESSAGE;
    //         $color = 'danger';
    //     }
    //     return RedirectType::REDIRECT_BACK($msg, $color);
    // }
}