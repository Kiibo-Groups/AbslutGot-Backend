<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class ItemAddon extends Authenticatable
{
    protected $table = "item_addon";
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

    public function addNew($data,$id)
    {
        ItemAddon::where('item_id',$id)->delete();

        if (isset($data['a_id'])) {
            $cate = isset($data['a_id']) ? $data['a_id'] : [];

            for($i=0;$i<count($cate);$i++)
            {

                $addon          = Addon::where('category_id',$cate[$i])->get();

                foreach ($addon as $a) {
                    $add            = new ItemAddon;
                    $add->item_id   = $id;
                    $add->addon_id  = $a->id;
                    $add->category_id  = $a->category_id;
                    $add->save();
                } 
            }
        }else {
            $a = isset($data['a_idAdd']) ? $data['a_idAdd'] : [];

            for($i=0;$i<count($a);$i++)
            {

                $cate_id          = Addon::find($a[$i]);
                $add            = new ItemAddon;
                $add->item_id   = $id;
                $add->addon_id  = $a[$i];
                $add->category_id  = $cate_id->category_id;
                $add->save();
                
            }
        }
    }

    public function getAssigned($id)
    {
        return ItemAddon::where('item_id',$id)->pluck('addon_id')->toArray();
    }

    public function getAssignedCate($id)
    {
        return ItemAddon::where('item_id',$id)->pluck('category_id')->toArray();
    }
}
