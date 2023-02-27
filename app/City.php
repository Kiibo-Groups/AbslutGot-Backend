<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;

class City extends Authenticatable
{
    protected $table = "city";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [
            'name'      => 'required',
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
        $a                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $add                = $type === 'add' ? new City : City::find($type);
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->lat           = isset($data['lat']) ? $data['lat'] : 0;
        $add->lng           = isset($data['lng']) ? $data['lng'] : 0;
        $add->h3index       = isset($data['h3index']) ? $data['h3index'] : 0;
        $add->max_distance  = isset($data['max_distance']) ? $data['max_distance'] : 0;
        $add->status        = isset($data['status']) ? $data['status'] : null;
        $add->c_type        = isset($data['c_type']) ? $data['c_type'] : 0;
        $add->c_value       = isset($data['c_value']) ? $data['c_value'] : 0;
        $add->min_distance  = isset($data['min_distance']) ? $data['min_distance'] : 0;
        $add->min_value     = isset($data['min_value']) ? $data['min_value'] : 0;
        $add->s_data        = serialize($a);
        $add->save();

    }   
    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($type = null)
    {
        return City::where(function($query) use($type) {

            if($type)
            {
                $query->where('status',$type);
            }

        })->orderBy('id','DESC')->get();
    }

    public function GetNearbyCity($type = null)
    {
        $lat = isset($_GET['lat']) ? $_GET['lat'] : 0;
        $lon = isset($_GET['lng']) ? $_GET['lng'] : 0;

        $data = [];
        $allCity = [];

        $nearby = City::where(function($query) use($type) {

            if($type)
            {
                $query->where('status',$type);
            }

        })->select('city.*',DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
        * cos(radians(city.lat)) 
        * cos(radians(city.lng) - radians(" . $lon . ")) 
        + sin(radians(" .$lat. ")) 
        * sin(radians(city.lat))) AS distance"))->orderBy('distance','ASC')->get();

        foreach ($nearby as $key) {

            if ($key->distance <= $key->max_distance) {
                $data[] = [
                    'id' => $key->id,
                    'name' => $key->name,
                    'lat'  => $key->lat,
                    'lng'  => $key->lng,
                    'status' => $key->status,
                    'distance' => $key->distance
                ];   
            }  
            
            $allCity[] = [
                'id' => $key->id,
                'name' => $key->name,
                'lat'  => $key->lat,
                'lng'  => $key->lng,
                'status' => $key->status,
                'distance' => $key->distance
            ];
        }

        if (count($data) > 0) {
            return ['nearby' => true, 'data' => $data];
        }else {
            return ['nearby' => false, 'data' => $allCity];
        }
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$id]) ? $data[$id] : null;
    }
}
