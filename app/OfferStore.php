<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class OfferStore extends Authenticatable
{
    protected $table = "offer_store";
    
    public function addNew($data,$id)
    {
        OfferStore::where('offer_id',$id)->delete();

        $uid = isset($data['store']) ? $data['store'] : [];
        
        for($i=0;$i<count($uid);$i++)
        {
           if(isset($uid[$i]))
           {
             $add                = new OfferStore;
             $add->store_id      = $uid[$i];
             $add->offer_id      = $id;
             $add->save();
           }
        }
    }

    public function getAssigned($id)
    {
        return OfferStore::where('offer_id',$id)->pluck('store_id')->toArray();
    }

    public function getOffer($id = 0)
    {
        $sid  = User::where('city_id',$id)->pluck('id');
        $id   = OfferStore::whereIn('store_id',$sid)->pluck('offer_id')->toArray();
        $res  = Offer::whereIn('id',array_unique($id))->where('status',0)->get();
        $data = [];

        foreach($res as $row)
        {
            $data[] = [

            'id'   => $row->id,
            'desc' => $row->description,
            'img'  => $row->img ? Asset('upload/offer/'.$row->img) : null  

            ];
        }

        return $data;
    }
}   
