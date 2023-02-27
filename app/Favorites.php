<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class Favorites extends Authenticatable
{
    protected $table = "favorites";

    public function addNew($data)
    {
        // verificamos si ya existe

        $req                = Favorites::where('user_id',$data['user_id'])->where('store_id',$data['store_id'])->first();

        if (!$req) {
            $add                 = new Favorites;
            $add->user_id        = isset($data['user_id']) ? $data['user_id'] : 0;
            $add->store_id       = isset($data['store_id']) ? $data['store_id'] : 0;
            $add->save();
        }

        return 'done';
    }

    public function GetFavorites($id)
    {

        $lat        = isset($_GET['lat']) ? $_GET['lat'] : 0;
        $lon        = isset($_GET['lng']) ? $_GET['lng'] : 0;

        $res = Favorites::where('user_id',$id)->get();
        $us  = new User;

        $data = [];

        foreach ($res as $raw) {

            $row = User::where('id',$raw->store_id)->select('users.*',DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
            * cos(radians(users.lat)) 
            * cos(radians(users.lng) - radians(" . $lon . ")) 
            + sin(radians(" .$lat. ")) 
            * sin(radians(users.lat))) AS distance"))->first();

            if ($row) {
                

                /****** Function IsClose or IsOpen ******************/
                    $op_time 	 = new Opening_times;
                    $open = false;
                    if ($row->open == false) {
                        $open 		 = ($op_time->ViewTime($row->id)['status'] != 0) ? true : false;
                    }else {
                        $open = false;
                    }
                /****** Function IsClose or IsOpen ******************/
                    $totalRate    = Rate::where('store_id',$row->id)->count();
                    $totalRateSum = Rate::where('store_id',$row->id)->sum('star');
                    
                    if($totalRate > 0)
                    {
                        $avg          = $totalRateSum / $totalRate;
                    }
                    else
                    {
                        $avg           = 0 ;
                    }

                /****** Favorites ******/
                    $chk_favs = Favorites::where('store_id',$row->id)->where('user_id',$id)->first();
                    if ($chk_favs) {
                        $favorite = true;
                    }else {
                        $favorite = false;
                    }
                /****** Favorites ******/
            
                $data[] = [
                    'id_fav'        => $raw->id,
                    'id'            => $row->id,
                    'title'         => $us->getLang($row->id,$_GET['lid'])['name'],
                    'img'           => Asset('upload/user/'.$row->img),
                    'logo'           => Asset('upload/user/logo/'.$row->logo),
                    'address'       => $us->getLang($row->id,$_GET['lid'])['address'],
                    'open'          => $open,
                    'rating'        => $avg > 0 ? number_format($avg, 1) : '0.0',
                    'person_cost'   => $row->person_cost,
                    'delivery_time' => $row->delivery_time,
                    'type'          => CategoryStore::find($row->type)->name,
                    'subtype'       => $row->subtype,
                    'delivery_charges_value' => $us->SetCommShip($row->id,$row->p_staff,$row->distance_max,$row->distance),
                    'max_distance'  => $us->GetMax_distance($row->id,$row->distance_max,$lat,$lon),
                    "distance_max"  => $row->distance_max,
                    'km'            => round($row->distance,2),
                    'favorite'      => $favorite
                ];
            }
        }

        return $data;
    }

    public function TrashFavorite($id, $user)
    {
        $res = Favorites::where('id',$id)->where('user_id',$user)->delete();
        return 'done';
    }
}
