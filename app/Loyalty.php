<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

use Auth;
class Loyalty extends Authenticatable
{
    protected $table = "loyalty";


    /*
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($data,$type)
    {
         
        $add                = $type === 'add' ? new Loyalty : Loyalty::find($type);
        $add->store_id      = Auth::user()->id;
        $add->title         = isset($data['title']) ? $data['title'] : '';
        $add->visits        = isset($data['visits']) ? $data['visits'] : 0;
        $add->consum_min    = isset($data['consum_min']) ? $data['consum_min'] : 0;
        $add->offers        = isset($data['offers']) ? $data['offers'] : 0; 
        $add->descript      = isset($data['descript']) ? $data['descript'] : '';

        $add->save();
        
        $ItemLoyalty = new ItemLoyalty;
        $ItemLoyalty->addNew($data,$add->id);
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($store = 0)
    { 
        $req = Loyalty::where('store_id',Auth::user()->id)->get();
        $data = [];

        if ($req) {
            return $req;
        }else {
            return new Loyalty;
        }
    }

    public function getItems($store = 0)
    {
        $req = Loyalty::where('store_id',Auth::user()->id)->first();
        $data = [];

        if ($req) {
            return ItemLoyalty::join('item','item_loyalty.item_id','=','item.id')
                   ->select('item_loyalty.*','item.name as item')->where('loyalty_id',$req->id)->pluck('item_id')->toArray();
            // foreach ($items as $its) {
            //     $item = Item::where('id',$its->item_id)->first();
            //     $data[] = $item;
            // }
        }else {
            $data = Item::where('item.store_id',Auth::user()->id)
            ->orderBy('item.id','DESC')->get();
        }

        return $data->pluck('id')->toArray();
        
    }
 
 
}
