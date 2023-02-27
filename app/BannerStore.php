<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class BannerStore extends Authenticatable
{
    protected $table = "banner_store";

    public function addNew($data,$id)
    {
        BannerStore::where('banner_id',$id)->delete();

        $store_id = isset($data['store']) ? $data['store'] : [];

        for($i=0;$i<count($store_id);$i++)
        {
            if(isset($store_id[$i]))
            {
                $add                = new BannerStore;
                $add->banner_id     = $id;
                $add->store_id      = $store_id[$i];
                $add->save();
            }
        }
    }
}
