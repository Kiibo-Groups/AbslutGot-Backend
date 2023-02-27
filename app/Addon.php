<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class Addon extends Authenticatable
{
    protected $table = "addon";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [
            'name'      => 'required',
            'price' => 'numeric|min:0'
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
    |--------------------------------------
    |Add/Update Data
    |--------------------------------------
    */
    public function addNew($data,$addon)
    {
        $a                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $add                = $addon === 'add' ? new Addon : Addon::find($addon);
        $add->store_id      = Auth::user()->id;
        $add->category_id   = isset($data['cate_id']) ? $data['cate_id'] : null;
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->price         = isset($data['price']) ? $data['price'] : 0;
        $add->s_data        = serialize($a);
        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        $id  = Auth::user()->id;
        return Addon::join('category','addon.category_id','=','category.id')
        ->select('addon.*','category.name as cate','category.id_element as id_element')
        ->where('addon.store_id',$id)
        ->orderBy('addon.id','DESC')->paginate(15);
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$id]) ? $data[$id] : null;
    }
}
