<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class Cart extends Authenticatable
{
    protected $table = 'cart';

    public function addNew($data)
    {
        $store = Item::find($data['id']);
        $this->checkStore($data,$store->store_id);

        // Verificamos si tiene un cupon de descuento aplicado si si se elimina para volver a calcular
        $checkCupon = CartCoupen::where('cart_no',$data['cart_no'])->first();
        if (isset($checkCupon->id)) {
            CartCoupen::where('cart_no',$data['cart_no'])->delete();
        }


        $check = Cart::where('cart_no',$data['cart_no'])->where('item_id',$data['id'])
                     ->where('qty_type',$data['qtype'])->first();

        $add                = new Cart; //!isset($check->id) ? new Cart : Cart::find($check->id);
        $add->cart_no       = isset($data['cart_no']) ? $data['cart_no'] : 0;
        $add->store_id      = $store->store_id;
        $add->item_id       = isset($data['id']) ? $data['id'] : 0;
        $add->price         = isset($data['price']) ? $data['price'] : 0;
        $add->price_comm    = isset($data['price_comm']) ? $data['price_comm'] : 0;
        $add->qty_type      = isset($data['qtype']) ? $data['qtype'] : 0;

        if (isset($data['qty'])) {
            $add->qty = $data['qty'];
        }else {
            if($data['type'] == 0)
            {
                $add->qty = $add->qty + 1;
            }
            else
            {
                $add->qty = $add->qty - 1;
            }
        }

        $add->save();

        Cart::where('qty',0)->delete();

        $addon = new CartAddon;
        $addon->addNew($data,$add->id);

        return [

        'count' => Cart::where('cart_no',$data['cart_no'])->count(),
        'cart'  => $this->getItemQty($data['cart_no'])

        ];
    }

    public function updateCart($id,$type)
    {
       
        if(isset($_GET['cart_no']))
        {
            $res            = Cart::where('cart_no',$_GET['cart_no'])->where('item_id',$id)->first();
            $qty            = $res->qty;
            $res->qty       = $qty - 1;
            $res->save();

            // Bajamos en Complementos
            $addons         = CartAddon::where('cart_id',$_GET['cart_no'])->where('item_id',$id)->get();

            foreach ($addons as $ads) {
                $ads->qty    = $type == 0 ? $qty - 1 : $qty + 1;
                $ads->save();
            }

            if($res->qty <= 0)
            {
                CartAddon::where('cart_id',$_GET['cart_no'])->where('item_id',$id)->delete();
                $res->delete();
            }

            CartCoupen::where('cart_no',$_GET['cart_no'])->delete();
            return $this->getItemQty($_GET['cart_no']);
        }
        else
        {
            
            $res            = Cart::find($id);
            
            $cart_no        = $res->cart_no;
            $qty            = $res->qty;
            $res->qty       = $type == 0 ? $qty - 1 : $qty + 1;
            $res->save();
            
             // Bajamos en Complementos
             $addons         = CartAddon::where('cart_id',$id)->where('item_id',$res->item_id)->get();

             foreach ($addons as $ads) {
                 $ads->qty    = $type == 0 ? $qty - 1 : $qty + 1;
                 $ads->save();
             }
 
             if($res->qty <= 0)
             {
                CartAddon::where('cart_id',$id)->where('item_id',$res->item_id)->delete();
                $res->delete();
            }

            CartCoupen::where('cart_no',$cart_no)->delete();
            return $this->getCart($cart_no);
            
        }
    }

    public function getItemQty($cart_no)
    {
        $id = Cart::select('item_id')->distinct()->where('cart_no',$cart_no)->get();

        $data = [];

        foreach($id as $i)
        {
            $qty = Cart::where('cart_no',$cart_no)->where('item_id',$i->item_id)->sum('qty');

            $data[] = ['item_id' => $i->item_id,'qty' => $qty];
        }

        return $data;
    }

    public function checkStore($data,$sid)
    {
        $count = Cart::where('cart_no',$data['cart_no'])->orderBy('id','DESC')->first();

        if(isset($count->id))
        {

            Cart::where('cart_no',$data['cart_no'])->where('store_id','!=',$sid)->delete();
        }
    }

    public function getCart($cartNo)
    {
        $res = Cart::join('item','cart.item_id','=','item.id')
                   ->select('item.name as item','item.img','cart.*')
                   ->where('cart.cart_no',$cartNo)
                   ->get();

        $data       = [];
        $city_id    = isset($_GET['city_id']) ? $_GET['city_id'] : 0;
        $real_lat   = isset($_GET['real_lat']) ? $_GET['real_lat'] : 0;
        $real_lng   = isset($_GET['real_lng']) ? $_GET['real_lng'] : 0;

        $u = new User;

        // Obtenemos los productos
        foreach($res as $row)
        {
            $data[] = [
                'id'       => $row->id,
                'store_id' => $row->store_id,
                'item_id'  => $row->item_id,
                'price'    => $row->price, 
                'qty'      => $row->qty,
                'item'     => $u->getLangItem($row->item_id,$_GET['lid'])['name'],
                'img'      => $row->img ? Asset('upload/item/'.$row->img) : null,
                'addon'    => $this->cartAddon($row->id,$row->item_id),
                'SubTotal' => $this->getTotalxItem($cartNo,$row->item_id)
            ];
        }

        // Obtenemos los descuentos
        $getCoupon = CartCoupen::where('cart_no',$cartNo)->first();
        if ($getCoupon) {
            $CodeCoupon = $getCoupon->offer_id;
            // Comprobamos si el comercio esta entre los comercios con cupon
            $commCup    = OfferStore::where('store_id',$row->store_id)->where('offer_id',$CodeCoupon)->first();
            if ($commCup) {
                $discount   = CartCoupen::where('cart_no',$cartNo)->sum('amount');
            }else {
                $discount   = 0;
            } 
        }else {
            $discount = 0;
        }

        // Obtenemos el total + Cargos de envio
        $item_total = $this->getTotal($cartNo);
        $d_charges  = $this->d_charges($item_total,$cartNo,$_GET['lat'],$_GET['lng'],$city_id);
        $c_charges  = isset($row->price_comm) ? $row->price_comm : 0;
        
        $total      = ($item_total - $discount) + $d_charges['costs_ship'];
        $sid        = Cart::where('cart_no',$cartNo)->select('store_id')->distinct()->first();

        $subTotal = ($item_total - $discount);
        
        // Carte/Wallet Cashback
        $purse_x_table = 0;
        $purse_x_pickup = 0;
        $purse_x_delivery = 0; 
        if (isset($sid)) {
            $store_d  = User::find($sid->store_id); 
            $purse_x_table = ($subTotal * $store_d->purse_x_table) / 100; 
            $purse_x_pickup = ($subTotal * $store_d->purse_x_pickup) / 100;
            $purse_x_delivery = ($subTotal * $store_d->purse_x_delivery) / 100;
        }

        //  Obtenemos la comision por ticket
        $service_fee = 0;
        $service_nearby = false;
        if (isset($row->store_id)) {
            $store      = User::find($row->store_id);
            $t_value    = $store->t_value;
            $t_type     = $store->t_type;
            // Calculamos la tarifa del servicio
            if ($t_type == 0) { // Valor Fijo
                $service_fee = $t_value;
            }else { // Valor en %
                $service_fee = ($item_total * $t_value) / 100;
            }

            // Verificamos la distancia real entre usuario y comercio
            $service_nearby    = $store->GetMax_distance($row->store_id,$store->distance_max,$real_lat,$real_lng);
        } 

        return [
            'data'           => $data,
            'item_total'     => $item_total,
            'd_charges'      => $d_charges,
            'c_charges'      => $c_charges,
            'total'          => ($total + $service_fee),
            'service_fee'    => $service_fee,
            'service_nearby' => $service_nearby,
            'subTotal'       => $item_total, //($item_total - $discount),
            'discount'       => $discount,
            'purse_x_table'  => $purse_x_table,
            'purse_x_pickup' => $purse_x_pickup,
            'purse_x_delivery' => $purse_x_delivery,
            'store'          => isset($sid->store_id) ? $u->getLang($sid->store_id,$_GET['lid'])['name'] : [],
            'store_id'       => isset($sid->store_id) ? $sid->store_id : 0,
            'store_status'   => isset($sid->store_id) ? $u->getLang($sid->store_id,$_GET['lid'])['open_status'] : false,
            'time_delivery'  => isset($sid->store_id) ? $u->getLang($sid->store_id,$_GET['lid'])['time_delivery'] : [],
            'currency'       => Admin::find(1)->currency
        ];
    }

    public function deleteAll($cartNo)
    {
        $cart = Cart::where('cart_no',$cartNo)->first();

        // Eliminamos complementos
        CartAddon::where('cart_id',$cartNo)->delete();
        // Eliminamos cupones 
        CartCoupen::where('cart_no',$cartNo)->delete();
        // Eliminamos Carrito
        Cart::where('cart_no',$cartNo)->delete();

        return 'done';
    }

    public function cartAddon($id,$item_id)
    {
        return CartAddon::join('addon','cart_addon.addon_id','=','addon.id')
                        ->select('addon.*','cart_addon.qty')
                        ->where('cart_addon.cart_id',$id)
                        ->where('cart_addon.item_id',$item_id)
                        ->get();
    }

    public function d_charges($total,$cartNo,$latD,$lngD,$city_id = 0)
    {
        $cart = Cart::where('cart_no',$cartNo)->first();
        $val = 0;
        
        $charge_delivy = ($city_id > 0) ? City::find($city_id) : Admin::find(1);  

        if(isset($cart->id))
        {
            $user = User::where('id',$cart->store_id)
                ->select('users.*',DB::raw("6371 * acos(cos(radians(" . $latD . "))
                * cos(radians(users.lat))
                * cos(radians(users.lng) - radians(" . $lngD . "))
                + sin(radians(" .$latD. "))
                * sin(radians(users.lat))) AS distance"))
                ->orderBy('distance','ASC')
            ->first();

            $UserTab = new User;
            $c_value = 0;
            $c_type  = 0;
            $min_value = 0;
            $min_distance = 0;
            $lat     = null;
            $lng     = null;
 
            $p_staff = $user->p_staff;
           
            switch ($p_staff) {
                case 0:  // Sin servicio a domicilio
                    $c_value = 0;
                    $c_type = 1;
                    $min_value = 0;    
                    $min_distance = 0;
                    break;
                case 1: // De administración
                    $c_value    = $charge_delivy->c_value;
                    $c_type     = $charge_delivy->c_type;
                    $min_value  = $charge_delivy->min_value;
                    $min_distance = $charge_delivy->min_distance;
                    break;
                case 2: // Cobros Del negocio
                    $c_value    = $user->delivery_charges_value;
                    $c_type     = $user->type_charges_value;
                    $min_value  = $user->delivery_min_charges_value;    
                    $min_distance = $user->delivery_min_distance;
                    break;
                default:
                    $c_value    = $charge_delivy->c_value;
                    $c_type     = $charge_delivy->c_type;
                    $min_value  = $charge_delivy->min_value;
                    $min_distance = $charge_delivy->min_distance;
                    break;
            }
           

            // Obtenemos la latitud y longitud del comercio para hacer la comparativa
            $lat     = $user->lat;
            $lng     = $user->lng;

            if($user->min_cart_value > 0)
            {
                if($total < $user->min_cart_value)
                { 
                    if ($c_type == 0) {
                        // Es un valor x KM
                        $val = $UserTab->Costs_shipKM($c_value,$min_distance,$min_value,$user->distance);
                    }else {
                        // es un valor fijo
                        $val = $this->checaValor($c_value);
                    }
                }
                else {
                    // Supero el valor minimo del carrito por lo tanto el envio es gratis
                    $val = $this->checaValor(0);
                }

            }else {
                
                if ($c_type == 0) {
                    // Es un valor x KM
                    $val = $UserTab->Costs_shipKM($c_value,$min_distance,$min_value,$user->distance);
                }else {
                    // es un valor fijo
                    $val = $this->checaValor($c_value);
                }
            } 
        }else {
            $val = $this->checaValor(0);
        }

        return $val;
    }

    public function getTotal($cartNo)
    {
        $total = [];
        $res = Cart::where('cart_no',$cartNo)->get();
        foreach($res as $row)
        {
            $total[] = $row->price * $row->qty;

            foreach($this->cartAddon($row->id,$row->item_id) as $addon)
            {
                $total[] = $addon->price * $addon->qty;
            }
        }

        return array_sum($total);
    }

    public function getTotalxItem($cartNo,$item_id)
    {
        $total = [];
        $res = Cart::where('cart_no',$cartNo)->where('item_id',$item_id)->get();
        foreach($res as $row)
        {
            $total[] = $row->price * $row->qty;

            foreach($this->cartAddon($row->id,$row->item_id) as $addon)
            {
                $total[] = $addon->price * $addon->qty;
            }
        }

        return array_sum($total);

    }

    function checaValor($numero){
        
        return [
            'costs_ship'    => round($numero,2),
            'duration'      => '0'
        ];
    } 

}
