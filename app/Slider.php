<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Slider extends Authenticatable
{
    protected $table = "slider";

     /*
    |--------------------------------------
    |Add/Update Data
    |--------------------------------------
    */
    public function addNew($data,$type)
    {
        $add                = $type === 'add' ? new Slider : Slider::find($type);
        $add->title         = isset($data['title']) ? $data['title'] : null;
        $add->sort_no       = isset($data['sort_no']) ? $data['sort_no'] : 0;

        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("upload/slider/", $filename);   
            $add->img = $filename;   
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
        return Slider::orderBy('sort_no','ASC')->get();
    }

    public function getAppData()
    {
        $data = [];

        foreach($this->getAll() as $row)
        {
            $data[] = ['title' => $row->title,'img' => Asset('upload/slider/'.$row->img)];
        }

        return $data;
    }
}
