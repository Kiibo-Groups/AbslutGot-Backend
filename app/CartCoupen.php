<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class CartCoupen extends Authenticatable
{
    protected $table = "cart_coupen";

    public function addNew($id,$cartNo)
    {
        $cart   = new Cart;
        $total  = $cart->getTotal($cartNo);
        $offer  = Offer::find($id);

        if($offer->min_cart_value > 0)
        {
            if($total >= $offer->min_cart_value)
            {
                $val = $this->getVal($total,$offer);
            }
            else
            {
                $val = 0;
            }
        }
        else
        {
           $val = $this->getVal($total,$offer);
        }

        if($val > 0)
        {
            CartCoupen::where('cart_no',$cartNo)->delete();

            $add = new CartCoupen;
            $add->cart_no = $cartNo;
            $add->offer_id = $offer->id;
            $add->code    = $offer->code;
            $add->amount  = $val;
            $add->save();

            return ['msg' => 'done','data' => $cart->getCart($cartNo)];
        }
        else
        {
            return ['msg' => 'Este cupón requiere un valor mínimo de $'.$offer->min_cart_value];
        }

    }


    public function getVal($total,$offer)
    {
        
        //0 = %
        if($offer->type == 0)
        {
            $val = $total * $offer->value / 100;
        }
        else
        {
            $val = $offer->value;
        }

        $return = $val;

        if($offer->upto > 0)
        {
            if($val >= $offer->upto)
            {
                $return = $offer->upto;
            }
        }

        return $return;
    }
}
