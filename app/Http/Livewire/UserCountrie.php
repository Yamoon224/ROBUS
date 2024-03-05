<?php

namespace App\Http\Livewire;

use App\Models\Sid\SidUser;
use Livewire\Component;

class UserCountrie extends Component
{

    public $countrie;
    public $typeUsers = "";

    public function mount($countrie)
    {
        $this->countrie = $countrie;
    }

    public function render()
    {

        $get_users =    SidUser::Where('country_id', $this->countrie)->paginate(20);

        if ($this->typeUsers != "") {

            $user_all_search = SidUser::Where('country_id', $this->countrie)->where($this->typeUsers, true)->paginate(20);

            return view('livewire.user-countrie', [
                'users_all'  => $user_all_search,
            ]);
        }

        return view('livewire.user-countrie', [
            'users_all'  => $get_users,
        ]);
    }
}
