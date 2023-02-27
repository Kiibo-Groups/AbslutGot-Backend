<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
class CartAddon extends Authenticatable
{
    protected $table = 'cart_addon';

    public function addNew($data,$id)
    {
        $addon = isset($data['addon']) ? $data['addon'] : [];

        for($i=0;$i<count($addon);$i++)
        {
            $add            = new CartAddon;
            $add->cart_id   = $id;
            $add->item_id   = $data['id'];
            $add->addon_id  = $addon[$i];
            $add->qty       = $data['qty'];
            $add->save();
        }
    }
}
