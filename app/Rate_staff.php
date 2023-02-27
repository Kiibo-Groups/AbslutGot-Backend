<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class Rate_staff extends Authenticatable
{
   protected $table = 'rate_staff';

   public function addNew($data)
   {
        // Agregamos nuevo    
        $chk              = Rate_staff::where('order_id',$data['order'])->where('d_boy',$data['dboy'])->first();

        if (!$chk) {
            $add              = new Rate_staff;
            $add->order_id    = isset($data['order']) ? $data['order'] : 0;
            $add->d_boy       = isset($data['dboy']) ? $data['dboy'] : 0;
            $add->status      = isset($data['status']) ? $data['status'] : 0;

            $add->save();
        }
        return ['data' => 'done'];
   }

   public function getAll($id)
   {
      $rate = Rate_staff::where('staff_id',$id)->get();

      $data = [];
      foreach ($rate as $key) {

         $order = Order::find($key->order_id);
         $store = User::find($key->store_id);

         $data[] = [
            'user'  => $order->name,
            'store' => $store->name,
            'data'  => $key
         ];
      }

      return $data;
   }

   public function GetRate($id)
   {
      return Rate_staff::where(function($query) use($id) {

         $query->where('rate_staff.d_boy',$id);

      })->join('delivery_boys','rate_staff.d_boy','=','delivery_boys.id')
      ->select('delivery_boys.name as dboy','rate_staff.*')
      ->orderBy('rate_staff.id','DESC')->get();
    
   }
}
