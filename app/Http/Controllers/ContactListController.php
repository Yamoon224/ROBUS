<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts\Contact;
use Illuminate\Support\Facades\DB;
use App\Models\Contacts\ContactList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Redirect\RedirectType;

class ContactListController extends Controller
{
    private function getContactListById(int $id) {
        $contact_list = ContactList::where('sid_user_id', Auth::user()->id)->where('id', $id)->first();
        return $contact_list;
    }

    public function browse(){
        $table = true;
        $contacts_list = ContactList::where('company_id', Auth::user()->company_id)->get();
        return view('pages.contacts.list.browse', compact('contacts_list', 'table'));
    }

    public function show(ContactList $key)
    {
        
        return view('pages.contacts.list.show', compact('key'));
      
    }

    public function edit($key){

        $item = $this->getContactListById($key);
        if(empty($item)){
            return RedirectType::REDIRECT_URI('contacts.list.browse');
        }
        return view('pages.contacts.list.edit', compact('item'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return RedirectType::REDIRECT_URI('contacts.list.add', [], RedirectType::DEFAULT_VALIDATION_MESSAGE);
        }
        try{
            $contact_list = ContactList::where('company_id', Auth::user()->company_id)->where('name', $request->name)->first();
            if(!empty($contact_list)){
                return RedirectType::REDIRECT_URI('contacts.list.add', [], "La liste ($request->name) existe déjà");
            }

            $item = new ContactList();
            $item->company_id = Auth::user()->company_id;
            
            $item->name = $request->name;
            $item->description = $request->description;
            $item->status = $request->status ? true : false;
            $item->created_by = Auth::user()->id;
            $item->save();

            return RedirectType::REDIRECT_URI('contacts.list.browse', [], 'Liste de contact créée avec succès', 'success');
        } catch (\Exception $exception){
            return RedirectType::REDIRECT_URI('contacts.list.add', [], RedirectType::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function update(Request $request,  $key)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return RedirectType::REDIRECT_URI('contacts.list.edit', ['key' => $key], RedirectType::DEFAULT_VALIDATION_MESSAGE);
        }
        try{
            $contact_list = ContactList::where('company_id', Auth::user()->company_id)
                ->where('name', $request->name)
                ->where('id', '!=', $key)
                ->first();
            if(!empty($contact_list)){
                return RedirectType::REDIRECT_URI('contacts.list.edit', ['key' => $key], 'Le nom de cette liste existe déjà');
            }

            $item = $this->getContactListById($key);
            
            $item->name = $request->name;
            $item->description = $request->description;
            $item->status = $request->status ? true : false;
            $item->save();
            return RedirectType::REDIRECT_URI('contacts.list.browse', [], RedirectType::DEFAULT_EDITED_MESSAGE, 'success');
        } catch (\Exception $exception){
            return RedirectType::REDIRECT_URI('contacts.list.edit', ['key' => $key], RedirectType::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function delete($key)
    {
        
        try{
            DB::beginTransaction();
            $item = $this->getContactListById($key);
            if(empty($item)) {
                return RedirectType::REDIRECT_URI('contacts.list.browse');
            }
            $contacts = Contact::where('contact_list_id', $key)->get();
            foreach($contacts as $contact) {
                $contact->deleted_by = Auth::user()->id;
                $contact->save();
                $contact->delete();
            }

            
            $item->deleted_by = Auth::user()->id;
            $item->save();
            $item->delete();
            DB::commit();

            $msg = RedirectType::DEFAULT_DELETED_MESSAGE;
            $color = 'success';
        }catch (\Exception $exception){
            DB::rollBack();
            $msg = RedirectType::DEFAULT_ERROR_MESSAGE;
            $color = 'danger';
        }
        return RedirectType::REDIRECT_BACK($msg, $color);
    }
}