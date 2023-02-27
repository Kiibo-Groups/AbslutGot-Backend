<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class Type extends Authenticatable
{
    protected $table = "type";

     /*
    |--------------------------------------
    |Add/Update Data
    |--------------------------------------
    */
    public function addNew($data,$type)
    {
        $add                = $type === 'add' ? new Type : Type::find($type);
        $add->store_id      = Auth::user()->id;
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->start_time    = isset($data['start_time']) ? $data['start_time'] : null;
        $add->end_time      = isset($data['end_time']) ? $data['end_time'] : null;
        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return Type::orderBy('id','DESC')->get();
    }
}
