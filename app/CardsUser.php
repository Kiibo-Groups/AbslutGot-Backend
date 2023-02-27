<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class CardsUser extends Authenticatable
{
    protected $table = "cards_user";

     /*
    |--------------------------------------
    |Add/Update Data
    |--------------------------------------
    */
    public function addNew($data,$type)
    {
        $add               = $type === 'add' ? new CardsUser : CardsUser::find($type);
        $add->user_id      = isset($data['user_id']) ? $data['user_id'] : 0;
        $add->token_card   = isset($data['token_card']) ? $data['token_card'] : '';
        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return CardsUser::orderBy('id','DESC')->get();
    }
}
