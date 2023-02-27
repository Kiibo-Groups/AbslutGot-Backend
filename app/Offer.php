<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Offer extends Authenticatable
{
    protected $table = "offer";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [

        'code'      => 'required',

        ];
    }
    
    public function validate($data,$type)
    {

        $validator = Validator::make($data,$this->rules($type));       
        if($validator->fails())
        {
            return $validator;
        }
    }

    /*
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($data,$type)
    {
        $a                      = isset($data['lid']) ? array_combine($data['lid'], $data['l_code']) : [];
        $b                      = isset($data['lid']) ? array_combine($data['lid'], $data['l_desc']) : [];
        $add                    = $type === 'add' ? new Offer : Offer::find($type);
        $add->code              = isset($data['code']) ? $data['code'] : null;
        $add->description       = isset($data['description']) ? $data['description'] : null;
        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("upload/offers/", $filename);   
            $add->img = $filename;   
        }

        $add->min_cart_value    = isset($data['min_cart_value']) ? $data['min_cart_value'] : 0;
        $add->upto              = isset($data['upto']) ? $data['upto'] : null;
        $add->type              = isset($data['type']) ? $data['type'] : 0;
        $add->value             = isset($data['value']) ? $data['value'] : 0;
        $add->status            = isset($data['status']) ? $data['status'] : 0;
        $add->start_from        = isset($data['start_from']) ? date('Y-m-d',strtotime($data['start_from'])) : null;
        $add->valid_till        = isset($data['valid_till']) ? date('Y-m-d',strtotime($data['valid_till'])) : null;
        $add->s_data            = serialize([$a,$b]);
        $add->save();

        $store = new OfferStore;

        $store->addNew($data,$add->id);
        
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($store = 0)
    {
        return Offer::where(function($query) use($store) {

            if($store > 0)
            {
                $query->where('store_id',$store);
            }

        })->orderBy('offer.id','DESC')->get();
    }

    public function getOffer($cartNo)
    {
        $cart = Cart::where('cart_no',$cartNo)->first();
        $id   = OfferStore::where('store_id',$cart->store_id)->pluck('offer_id')->toArray();
        $res  = Offer::whereIn('id',$id)->where('status',0)->get();
        $data = [];

        foreach($res as $row)
        {
            $data[] = [
                'id'        => $row->id,
                'code'      => $row->code,
                'img'       => $row->img ? Asset('upload/offers/'.$row->img) : null,
                'desc'      => $row->description,
                'min_cart'  => $row->min_cart_value,
                'type'      => $row->type,
                'value'     => $row->value
            ];
        }

        return $data;
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$field][$id]) ? $data[$field][$id] : null;
    }
}
