<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Banner extends Authenticatable
{
    protected $table = "banner";

     /*
    |--------------------------------------
    |Add/Update Data
    |--------------------------------------
    */
    public function addNew($data,$type)
    {
        $add                    = $type === 'add' ? new Banner : Banner::find($type);
        $add->city_id           = isset($data['city_id']) ? $data['city_id'] : 0;
        $add->status            = isset($data['status']) ? $data['status'] : 0;
        $add->design_type       = isset($data['design_type']) ? $data['design_type'] : 0;
        $add->position          = isset($data['position']) ? $data['position'] : 0;

        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("upload/banner/", $filename);   
            $add->img = $filename;   
        }

        $add->save();

        $store = new BannerStore;
        $store->addNew($data,$add->id);
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($city = 0,$type = 'all')
    {
        return Banner::where(function($query) use($city,$type){

            if($type !== 'all')
            {
                $query->where('banner.position',$type);
            }

            if($city > 0)
            {
                $query->where('banner.status',0)->whereIn('banner.city_id',[0,$city]);
            }


        })->leftjoin('city','banner.city_id','=','city.id')
                     ->select('city.name as city','banner.*')
                     ->orderBy('id','DESC')->get();
    }

    public function getPosition($type)
    {
        if($type == 0)
        {
            $return = "Top";
        }
        elseif($type == 1)
        {
            $return = "Middle";
        }
        else
        {
            $return = "Bottom";
        }

        return $return;
    }

    public function getAppData($id,$type)
    {
        $data = [];

        foreach($this->getAll($id,$type) as $row)
        {
            $link   = BannerStore::where('banner_id',$row->id)->pluck('store_id')->toArray();
            $data[] = [

            'id'        => $row->id,
            'img'       => Asset('upload/banner/'.$row->img),
            'type'      => $row->design_type,
            'position'  => $row->position,
            'link'      => count($link) > 0 ? true : false

            ];
        }

        return $data;
    }
}
