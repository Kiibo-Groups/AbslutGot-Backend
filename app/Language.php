<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Language extends Authenticatable
{
    protected $table = "language";

    public function addNew($data,$type)
    {
        $add                = $type === 'add' ? new Language : Language::find($type);
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->type          = isset($data['type']) ? $data['type'] : 0;
        $add->sort_no       = isset($data['sort_no']) ? $data['sort_no'] : 0;

        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("upload/language/", $filename);   
            $add->icon = $filename;   
        }

        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return Language::orderBy('sort_no','ASC')->get();
    }

    public function getWithEng()
    {
        $res   =  Language::select('id','name','icon','type')->orderBy('sort_no','ASC')->get()->toArray();
        $en[]  = ['id' => 0,'name' => 'English','icon' => 'en.png','type' => 0];

        $all = array_merge($en,$res);

        $data = [];

        foreach($all as $a)
        {
            $img    = Asset('upload/language/'.$a['icon']);
            $data[] = ['id' => $a['id'],'name' => $a['name'],'icon' => $a['icon'],'img' => $img,'type' => $a['type']];
        }

        return $data;
    }
}
