<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
class OrderAddon extends Authenticatable
{
    protected $table = 'order_addon';

    public function addNew($id,$cartNo)
    {
        $cid  = Cart::where('cart_no',$cartNo)->get();

        foreach ($cid as $order) {
            $cart = CartAddon::where('cart_id',$order->id)->get();
            foreach($cart as $c)
            {
                $add             = new OrderAddon;
                $add->order_id   = $id;
                $add->cart_no    = $order->id;
                $add->item_id    = $c->item_id;
                $add->addon_id   = $c->addon_id;
                $add->qty        = $c->qty;
                $add->save();
            }

            CartAddon::where('cart_id',$order->id)->delete();
        }

    }
}
