<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class ItemLoyalty extends Authenticatable
{
    protected $table = "item_loyalty";
   

    public function addNew($data,$id)
    {
        // Eliminamos todo lo relacionado
        ItemLoyalty::where('loyalty_id',$id)->delete();
        
        // Agregamos los nuevos elementos
        $its = isset($data['items']) ? $data['items'] : [];
        for($i=0;$i<count($its);$i++)
        {  
            $add             = new ItemLoyalty;
            $add->loyalty_id = $id;
            $add->item_id    = $its[$i];
            $add->save();
        }
    }
 
}
