<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class Rate extends Authenticatable
{
   protected $table = 'rate';

   public function addNew($data)
   {
      $order            = Order::find($data['oid']);
      $chk              = Rate::where('order_id',$data['oid'])->get();
      // Agregamos nuevo
      $add->user_id     = $data['user_id'];
      $add->store_id    = $order->store_id;
      $add->staff_id    = $order->d_boy;
      $add->order_id    = $data['oid'];
      $add->event_id    = 0;
      $add->star        = $data['star'];
      $add->star_staff1 = isset($data['star_staff1']) ? $data['star_staff1'] : 0;
      $add->star_staff2 = isset($data['star_staff2']) ? $data['star_staff2'] : 0;

      if ($data['type'] == 'staff') {
         $add->comment_staff     = isset($data['comment']) ? $data['comment'] : '';
      }else {
         $add->comment     = isset($data['comment']) ? $data['comment'] : '';
      }
      
      $add->save();
     
      $order->status = 6;
      $order->save();

      // Notificamos
      $msg = "El usuario ha calificado tu servicio con ".$data['star'].' estrellas.';
      $title = "Te han calificado por tu servicio.";
      app('App\Http\Controllers\Controller')->sendPushD($title,$msg,$$order->d_boy);
      

      return ['data' => true];
   }

   public function getAll($id)
   {
      $rate = Rate::where('staff_id',$id)->get();

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
      return Rate::where(function($query) use($id) {

         $query->where('rate.staff_id',$id);

      })->join('app_user','rate.user_id','=','app_user.id')
      ->select('app_user.name as user','rate.*')
      ->orderBy('rate.id','DESC')->get();
    
   }
}
