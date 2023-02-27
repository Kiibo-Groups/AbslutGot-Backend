<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use Mail;
use DB;

class Order_staff extends Authenticatable

{

    protected $table = 'orders_staff';

    /*
    |--------------------------------
    |Create/Update Orders Ext
    |--------------------------------
    */
    public function addNew($order_id,$type)
    {
        $chk               = Order_staff::where('order_id',$order_id);
        
        if ($type == 'spadmin') {
            $add               = new Order_staff;
            $order             = Order::find($order_id);
            $add->order_id     = $order_id;
            $add->d_boy        = $order->d_boy;
            $add->status       = $order->status;
            $add->save();
        }else {
            if ($chk) {
                Order_staff::where('order_id',$order_id)->delete();
            }
            $add               = new Order_staff;
            $add->order_id     = $order_id;
            $add->d_boy        = 0;
            $add->status       = 1;
            $add->save();

             // Envio de notificaciones
            
        }

    }

}



?>