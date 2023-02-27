<?php

namespace App;

use App\Http\Controllers\NodejsServer;

use SimpleSoftwareIO\QrCode\Facades\QrCode; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use Mail;
use DB;
class Order extends Authenticatable
{
   protected $table = 'orders';

   public function addNew($data)
   { 
      $store               = User::find($this->getStore($data['cart_no']));

      $addStatus           = false;
      $address             = $data['address']; // Direccion de entrega
      $real_lat            = isset($data['real_lat']) ? $data['real_lat'] : 0;
      $real_lng            = isset($data['real_lng']) ? $data['real_lng'] : 0;

      $latD                = isset($data['lat']) ? $data['lat'] : 0;;
      $lngD                = isset($data['lng']) ? $data['lng'] : 0;;
 
      
      if (isset($data['otype']) && $data['otype'] == 1) { 
         // Verificamos la distancia real entre usuario y comercio
         $dataMD    = $store->GetMax_distance($store->id,$store->distance_max,$real_lat,$real_lng);
         // Envio a domicilio
         if ($dataMD == 0) {
            // No hay servicio
            return ['data' => "Not_service"];
         }else {
            $addStatus = true;
         }
      }else {
         $addStatus = true;
      }

      if ($addStatus == true) {
         $add                 = new Order;
         $add->cart_no        = $data['cart_no'];
         $add->user_id        = $data['user_id'];
         $add->store_id       = $this->getStore($data['cart_no']);
         $add->name           = isset($data['user_name']) ? $data['user_name'] : 'Indefinido';
         $add->email          = isset($data['user_email']) ? $data['user_email'] : 'Indefinido';
         $add->phone          = isset($data['user_phone']) ? $data['user_phone'] : 'Indefinido';
         $add->address        = isset($data['address']) ? $data['address'] : 'Indefinido';
         $add->lat            = isset($data['lat']) ? $data['lat'] : null;
         $add->lng            = isset($data['lng']) ? $data['lng'] : null;
         $add->reallat        = isset($data['real_lat']) ? $data['real_lat'] : 0;
         $add->reallng        = isset($data['real_lng']) ? $data['real_lng'] : 0;
         $add->InnStore       = isset($data['InnStore']) ? $data['InnStore'] : 0;
         $add->mesa           = isset($data['mesa']) ? $data['mesa'] : 0;
         $add->type           = isset($data['otype']) ? $data['otype'] : 1;
         $add->price_comm     = $this->getTotal($data['cart_no'],$data['otype'],$data['lat'],$data['lng'],$store->city_id)['Price_comm'];
         $add->d_charges      = $this->getTotal($data['cart_no'],$data['otype'],$data['lat'],$data['lng'],$store->city_id)['d_charges'];
         $add->t_charges      = $this->getTotal($data['cart_no'],$data['otype'],$data['lat'],$data['lng'],$store->city_id)['service_fee'];
         $add->discount       = $this->getTotal($data['cart_no'],$data['otype'],$data['lat'],$data['lng'],$store->city_id)['discount'];
         $add->total          = $this->getTotal($data['cart_no'],$data['otype'],$data['lat'],$data['lng'],$store->city_id)['total'];
         $add->payment_method = isset($data['payment']) ? $data['payment'] : 1;
         $add->payment_id     = isset($data['payment_id']) ? $data['payment_id'] : 0;
         $add->notes          = isset($data['notes']) ? $data['notes'] : '';
         $add->save();

         /** Generamos Codigo de entrega */
            $key = '';
            $pattern = $add->id.'1234567890'.$data['cart_no'];
            $key = substr(md5($pattern),0,8);
            // Creamos el QR https://{{$add->dominio}}.kiibo.mx
            $link_qr        = "https://bincar.kiibo.mx/track_order?id=".strtoupper($key);
            $codeQR         = base64_encode(QrCode::format('png')->size(200)->generate($link_qr));

            $add->code_order   = $codeQR;
            $add->external_id = strtoupper($key);
            $add->save();
         /** Generamos Codigo de entrega */

         /** Agregamos los elementos del menu */
            $item = new OrderItem;
            $item->addNew($add->id,$data['cart_no']);
         /** Agregamos los elementos del menu */

         /** Agregamos los complementos */
            $addon = new OrderAddon;
            $addon->addNew($add->id,$data['cart_no']);
         /** Agregamos los complementos */

         $admin = Admin::find(1);

         /** Generamos el Enlace para el envio del Whatsapp */
            $dnl = "\r\n";
            $ddnl = "\n\n";
            $nl="\n";
            $tabSpace="      ";

            $msg = 'Nuevo Pedido #'.$add->id.$dnl;
            $msg .= "Fecha ".date('d-M-Y',strtotime($add->created_at))." | ".date('h:i:A',strtotime($add->created_at)).$ddnl;

            $msg .= "Hola, Vengo de https://".$store->dominio.".kiibo.mx".$ddnl;

            $msg .= "* Tipo de servicio: ";
            $msg .= ($add->type == 1) ? " Servicio a domcilio".$dnl : " Recoger en Tienda *".$dnl;

            $msg .= $nl;

            $msg .= "Nombre: ".$add->name.$dnl;
            $msg .= "Telefono: ".$add->phone.$dnl;
            $msg .= "Dirección: ".$add->address.$dnl;

            $msg .= $nl;
            if($add->type == 1)
            {
               $msg .= "(Negocio) - Compartir con tu repartidor";
               $msg .= $nl;
               $msg .= "https://www.google.com/maps/dir/".$store->lat.",".$store->lng."/".$add->lat.",".$add->lng;
               $msg .= $nl;
               $msg .= $nl;
            }
            
            
            $msg .= "Ubicación real del dispositivo";
            $msg .= $nl;
            $msg .= "https://www.google.com/maps?q=".$add->reallat.",".$add->reallng.$nl;
            $msg .= $nl;

            $msg .= "Método de pago solicitado: ";
            if ($add->payment_method == 1) { // Pago en Efectivo
               $msg .= "Pago en Efectivo".$dnl;
            }

            if ($add->payment_method == 2) { // Pago via Stripe
               $msg .= "Pago con Tarjeta de Crédito/Débito: ".$dnl;
               $msg .= "ID del pago: ".$add->payment_id.$dnl;
            }
 
            $msg .= $nl;
            $msg .= "Costos: ".$dnl;
            $msg .= $nl;
            $msg .= "Costo de los productos: $".($item->getTotal($add->id) - $add->discount).$dnl;

            if ($add->type == 1) {
               $msg .= "Costo de envio: $".$add->d_charges.$dnl;
            }
            
            if ($add->payment_method == 2) {
               $msg .= "Cargos por pago con tarjeta: $".$admin->comm_stripe."%".$dnl;
            }

            $msg .= "Total a pagar: $".$add->total.$dnl;

            $msg .= $nl;  
            $msg .= "Pedido".$dnl;
            $msg .= $nl;

            $prods = Cart::where('cart_no',$data['cart_no'])->get();
            foreach($prods as $row)
            {
               $item_prod        = Item::find($row->item_id);
               $msg .= "* x".$row->qty." ".$item_prod->name."  $".$row->price.$ddnl;
               
               // Complementos
               $addons = CartAddon::where('cart_id',$row->id)->get();
               
               if (count($addons) > 0) {
                  $msg .= "    Complementos: ".$dnl;
               
                  foreach ($addons as $ads) {
                     
                     $addon = Addon::find($ads->addon_id);

                     $msg .= "    x".$ads->qty." ".$addon->name."  $".$addon->price;
                  }

                  $msg .= $ddnl;
                  $msg .= "---------------------------------".$ddnl;
               
               }
            }

            $msg .= $nl;
            $msg .= $nl; 

            $msg .= "Para darle seguimiento al pedido, ingresa ha: ".$link_qr;
            
            $msg .= $nl; 
            $msg .= "Envía este mensaje. Te atenderemos enseguida.";


            // Quitamos espacios del telefiono
            $phone = str_replace(' ','',$store->phone);
            $phone = str_replace('-','',$store->phone);
            $phone = str_replace('+','',$store->phone);

            $url = 'https://wa.me/+521'.$phone.'?text='.urlencode($msg);
         /** Generamos el Enlace para el envio del Whatsapp */

         /** Eliminamos el carrito y los cupones en caso de existir */
            Cart::where('cart_no',$data['cart_no'])->delete();
            CartAddon::where('cart_id',$data['cart_no'])->delete();
            CartCoupen::where('cart_no',$data['cart_no'])->delete();
         /** Eliminamos el carrito y los cupones en caso de existir */

         /** Notificamos al panel de control */ 
         $title = "Nuevo pedido recibido!!";
         $message = " 🎉 Nuevo pedido recibido 🎉 #".$add->id." valor del pedido $".$add->total;

         app('App\Http\Controllers\Controller')->sendWeb($title,$message,strval($store->id));
         /** Notificamos al panel de control */

         return  $url;
      }
   }

   public function getStore($cartNo)
   {
      return Cart::where('cart_no',$cartNo)->first()->store_id;
   }

   public function getTotal($cartNo,$type,$latD,$lngD,$city_id)
   {

      $cart       = new Cart;
      $item_total = $cart->getTotal($cartNo);
      $d_charges  = $cart->d_charges($item_total,$cartNo,$latD,$lngD,$city_id);
     
      $price_comm = Cart::where('cart_no',$cartNo)->first()->price_comm;
      $discount   = CartCoupen::where('cart_no',$cartNo)->sum('amount');
      $subTotal   = ($item_total - $discount);

      if ($type == 2) {
         $total      = ($item_total - $discount);
      }else {
         $total      = ($item_total - $discount) + $d_charges['costs_ship'];
      }

      //  Obtenemos la comision por ticket
      $service_fee = 0;
      $store_id    = Cart::where('cart_no',$cartNo)->first()->store_id;
      $store       = User::find($store_id);
      $t_value     = $store->t_value;
      $t_type      = $store->t_type;
      // Calculamos la tarifa del servicio
      if ($t_type == 0) { // Valor Fijo
         $service_fee = $t_value;
      }else { // Valor en %
         $service_fee = ($item_total * $t_value) / 100;
      }


      return [
         'total' => ($total + $service_fee),
         'subTotal' => $subTotal,
         'discount' => $discount,
         'd_charges' => $d_charges['costs_ship'],
         'Price_comm' => $price_comm,
         'service_fee' => $service_fee,
         'item_total' => $item_total
      ];
   }

   public function history($id)
   {
      $data     = [];
      $currency = Admin::find(1)->currency;

      $orders = Order::where(function($query) use($id){

         if($id > 0)
         {
            $query->where('orders.user_id',$id);
         }

         if(isset($_GET['id']))
         {
            $query->where('orders.d_boy',$_GET['id']);
         }

         if(isset($_GET['status']))
         {
            if($_GET['status'] == 3)
            {
               $query->whereIn('orders.status',[1.5,3,4]);
            }
            else
            {
               $query->where('orders.status',6);
            }
         }

      })->join('users','orders.store_id','=','users.id')
         ->leftjoin('delivery_boys','orders.d_boy','=','delivery_boys.id')
         ->select('users.name as store','orders.*','delivery_boys.name as dboy')
         ->orderBy('id','DESC')
         ->get();

      $u = new User;

      foreach($orders as $order)
      {
         $items = [];
         $i     = new OrderItem;

         if($order->status == 0)
         {
            $status = "Pendiente";
         }
         elseif($order->status == 1)
         {
            $status = "Confirmada";
         }
         elseif($order->status == 2)
         {
            $status = "Cancelada";
         }
         elseif($order->status == 3)
         {
            $status = "Elegido para entregar por ".$order->dboy;
         }
         else
         {
            $status = "Entregado en ".$order->status_time;
         }

         $countRate = Rate::where('order_id',$order->id)->where('user_id',$id)->first();
         $tot_com   = $order->total - $order->d_charges;

         $data[] = [
            'id'        => $order->id,
            'store'     => User::find($order->store_id),
            'code_order' => $order->code_order,
            'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
            'total'     => $order->total,
            'tot_com'   => $tot_com, //$i->RealTotal($order->id),
            'd_charges' => $order->d_charges,
            'items'     => $i->getItem($order->id),
            'status'    => $status,
            'st'        => $order->status,
            'stime'     => $order->status_time,
            'sid'       => $order->store_id,
            'hasRating' => isset($countRate->id) ? $countRate->star : 0,
            'ratStaff'  => isset($countRate->staff_id) ? $countRate->staff_id : 0,
            'ratStore'  => isset($countRate->store_id) ? $countRate->store_id : 0, 
            'currency'  => $currency,
            'user'      => $order,
            'pay'       => $order->payment_method
         ];
      }

      return $data;
   }

   public function history_ext($id)
   {
      $data     = [];
      $currency = Admin::find(1)->currency;

      $orders = Order_staff::where(function($query) use($id){

         if(isset($_GET['id']))
         {
            $query->whereIn('orders_staff.d_boy',[$_GET['id']]);
         }

         if(isset($_GET['status']))
         {
            if($_GET['status'] == 1)
            {
               $query->whereIn('orders_staff.status',[0,1,2,3,4]);
            }
         }

      })->get();

      if ($orders->count() > 0) {

         foreach($orders as $pedido)
         {
            if ($pedido->type == 1) {
               // Es un mandadito
               $order = Commaned::find($pedido->event_id);

               $items = [];
              
                  if($order->status == 0)
                  {
                     $status = "Buscando repartidor";
                  }
                  elseif($order->status == 1)
                  {
                     $status = "Confirmado";
                  }
                  elseif($order->status == 2)
                  {
                     $status = "Cancelada";
                  }
                  elseif($order->status == 3)
                  {
                     $status = "Repartidor no encontrado";
                  }
                  elseif($order->status == 4)
                  {
                     $status = "Pedido recolectado";
                  }
                  elseif($order->status == 4.5)
                  {
                     $status = "Pedido en camino";
                  }
                  else
                  {
                     $status = "Entregado en ".$order->status_time;
                  }

               $countRate = Rate::where('event_id',$order->id)->where('staff_id',$id)->first();
               $tot_com   = $order->total - $order->d_charges;

               $data[] = [
                  'type'      => 'comanded',
                  'id'        => $order->id,
                  'user'      => AppUser::find($order->user_id),
                  'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
                  'total'     => $order->total,
                  'd_charges' => $order->d_charges,
                  'tot_com'   => $tot_com, //$i->RealTotal($order->id),
                  'st'        => $order->status,
                  'stime'     => $order->status_time,
                  'sid'       => $order->user_id,
                  'hasRating' => isset($countRate->id) ? $countRate->star : 0,
                  'currency'  => $currency,
                  'pay'       => $order->payment_method,
                  'comm'      => $order
               ];
            }else {

               $order = Order::find($pedido->order_id);

               $items = [];
               $i     = new OrderItem;

               if($order->status == 0)
               {
                  $status = "Pendiente";
               }
               elseif($order->status == 1)
               {
                  $status = "Confirmada";
               }
               elseif($order->status == 2)
               {
                  $status = "Cancelada";
               }
               elseif($order->status == 3)
               {
                  $status = "Elegido para entregar por ".$order->dboy;
               }
               else
               {
                  $status = "Entregado en ".$order->status_time;
               }

               $countRate = Rate::where('order_id',$order->id)->where('user_id',$id)->first();
               $tot_com   = $order->total - $order->d_charges;

               $data[] = [
                  'type'      => 'delivery',
                  'id'        => $order->id,
                  'store'     => User::find($order->store_id),
                  'code_order' => $order->code_order,
                  'external_id' => $order->external_id,
                  'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
                  'total'     => $order->total,
                  'd_charges' => $order->d_charges,
                  'tot_com'   => $tot_com, //$i->RealTotal($order->id),
                  'items'     => $i->getItem($order->id),
                  'st'        => $order->status,
                  'stime'     => $order->status_time,
                  'sid'       => $order->store_id,
                  'hasRating' => isset($countRate->id) ? $countRate->star : 0,
                  'currency'  => $currency,
                  'user'      => $order,
                  'pay'       => $order->payment_method
               ];
            }
         }
      }
      return $data;
   }

   public function getListOrder($id)
   {
      $req = Order::where(function($query) use($id){
         $query->where('orders.cart_no',$id);
         $query->whereIn('orders.status',[0,1,1.5,3,4,5]);
     })->orderBy('id','DESC')
     ->get();
     
     $data = [];

     foreach ($req as $key) {
         
         $data[] = [
             'dboy' => ($key->d_boy != 0) ? Delivery::find($key->d_boy) : [],
             'store' => ($key->store_id != 0) ? User::find($key->store_id): [],
             'order' => $key
         ];
     }

     return $data;
   }

   public function getAll($type = null,$store_id = 0)
   {
      if (Auth::guard('admin')->user()) {
         $city_id = Auth::guard('admin')->user()->city_id;
      }else {
         $city_id = 0;
      }

      $res  =  User::whereRaw('lower(city_id) like "%' . strtolower($city_id) . '%"')->pluck('id')->toArray();
      $string_form_array = implode(' , ',$res);

      if($city_id == 0){
            $take  = $type ? 15 : "";

         return $data = Order::where(function($query) use($store_id) {

            if(isset($_GET['status']))
            {
               if($_GET['status'] == 1 && !isset($_GET['type']))
               {
                  $query->whereIn('orders.status',[1,1.5,3,4]);
               }
               elseif ($_GET['status'] == 5) {
                  $query->whereIn('orders.status',[5,6]);
               }
               else
               {
                  $query->where('orders.status',$_GET['status']);
               }
            }

            if($store_id > 0)
            {
               $query->where('orders.store_id',$store_id);
            }

            if(isset($_GET['type']))
            {
               $query->where('orders.store_id',Auth::user()->id);
            }

         })->join('users','orders.store_id','=','users.id')
            ->select('users.c_type as comm_type')
         ->leftjoin('delivery_boys','orders.d_boy','=','delivery_boys.id')
         ->select('users.name as store','orders.*','delivery_boys.name as dboy')
         ->orderBy('orders.id','DESC')
         ->take($take)
         ->paginate(20);
         dd($data);
      }else {
         $take  = $type ? 15 : "";

         return Order::where(function($query) use($store_id) {

         if(isset($_GET['status']))
         {
            if($_GET['status'] == 1 && !isset($_GET['type']))
            {
               $query->whereIn('orders.status',[1,3]);
            }
            else
            {
               $query->where('orders.status',$_GET['status']);
            }
         }

         if($store_id > 0)
         {
            $query->where('orders.store_id',$store_id);
         }

         if(isset($_GET['type']))
         {
            $query->where('orders.store_id',Auth::user()->id);
         }

         })->join('users','orders.store_id','=','users.id')
         ->select('users.c_type as comm_type')
         ->leftjoin('delivery_boys','orders.d_boy','=','delivery_boys.id')
         ->select('users.name as store','orders.*','delivery_boys.name as dboy')->whereIn('orders.store_id', [ $string_form_array])
         ->orderBy('orders.id','DESC')
         ->take($take)
         ->paginate(20);
      }
   }

   public function getKitchenOrder($type = null,$store_id = 0)
   {
       
      $orders = Order::where(function($query) use($store_id){
         $query->where('orders.store_id',$store_id);
         $query->whereIn('orders.status',[1,1.5,3]);
      })->join('users','orders.store_id','=','users.id')
      ->leftjoin('delivery_boys','orders.d_boy','=','delivery_boys.id')
      ->select('users.name as store','orders.*','delivery_boys.name as dboy')
      ->orderBy('id','ASC')
      ->get();
      
      $data = [];
      $u = new User;

      foreach($orders as $order)
      {
         $items = [];
         $i     = new OrderItem;

         if($order->status == 0)
         {
            $status = "Pendiente";
         }
         elseif($order->status == 1)
         {
            $status = "Confirmada";
         }
         elseif($order->status == 2)
         {
            $status = "Cancelada";
         }
         elseif($order->status == 3)
         {
            $status = "Listo para entregar";
         }
         elseif($order->status == 4)
         {
            $status = "Pedido Entregado";
         }
         else
         {
            $status = "Pedido Entregado";
         }

         $countRate = Rate::where('order_id',$order->id)->where('user_id',$store_id)->first();
         $tot_com   = $order->total - $order->d_charges;

         $data[] = [
            'id'        => $order->id, 
            'code_order' => $order->code_order,
            'external_id' => $order->external_id,
            'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
            'total'     => $order->total,
            'tot_com'   => $tot_com, //$i->RealTotal($order->id),
            'd_charges' => $order->d_charges,
            'items'     => $i->getItem($order->id),
            'notes'     => $order->notes,
            'status'    => $status,
            'st'        => $order->status,
            'stime'     => $order->status_time,
            'sid'       => $order->store_id,
            'hasRating' => isset($countRate->id) ? $countRate->star : 0,
            'ratStaff'  => isset($countRate->staff_id) ? $countRate->staff_id : 0,
            'ratStore'  => isset($countRate->store_id) ? $countRate->store_id : 0,  
            'pay'       => $order->payment_method
         ];
      }

      return $data;
		 
   }

   public function getType($id)
   {
      $res = Order::find($id);

      if($res->status_by == 1)
      {
         $return = "Admin";
      }
      elseif($res->status_by == 2)
      {
         $return = "Store";
      }
      elseif($res->status_by == 3)
      {
         $return = "User";
      }

      return $return;
   }

   public function signleOrder($id)
   {
      return Order::join('users','orders.store_id','=','users.id')
                 ->select('users.name as store','orders.*')
                 ->where('orders.id',$id)
                 ->first();
   }

   public function cancelOrder($id,$uid)
   {
      $res              = Order::find($id);
      $res->status      = 2;
      $res->status_by   = 3;
      $res->status_time    = date('d-M-Y').' | '.date('h:i:A');
      $res->save();

      app('App\Http\Controllers\Controller')->sendPushS("Pedido Cancelado","El pedido #".$id." ha sido cancelado por el usuario.",$res->store_id);

      return ['data' => $this->history($res->user_id)];
   }

   public function getReport($data)
   {
      $res = Order::where(function($query) use($data) {

         if(isset($data['from']))
         {
            $from = date('Y-m-d',strtotime($data['from']));
         }
         else
         {
            $from = null;
         }

         if(isset($data['to']))
         {
            $to = date('Y-m-d',strtotime($data['to']));
         }
         else
         {
            $to = null;
         }

         if($from)
         {
            $query->whereDate('orders.created_at','>=',$from);
         }

         if($to)
         {
            $query->whereDate('orders.created_at','<=',$to);
         }

         if($data['store_id'])
         {
            $query->where('orders.store_id',$data['store_id']);
         }

      })->join('users','orders.store_id','=','users.id')
        ->select('users.name as store','orders.*')
        ->orderBy('orders.id','ASC')->get();

      $allData = [];

      foreach($res as $row)
      {


         // Obtenemos datos del comercio
            $city_search = User::find($row->store_id);
            $city_id     = $city_search->city_id;
            $delivery_charges = $city_search->delivery_charges_value;
            $c_type      = $city_search->c_type;
            $c_value     = $city_search->c_value;
            $city        = City::find($city_id);

         // Obtenemos los datos del usuario
            $user_search = AppUser::find($row->user_id);
            if ($user_search) {
               $user_email  = $user_search->email;
               $user_phone  = $user_search->phone;
            }else {
               $user_email  = "Indefinido";
               $user_phone  = "Indefinido";
            }

         // Obtenemos el Type
            if ($row->type == 2) {
               $type = 'Recogido en tienda';
            }else {
               $type = 'Enviado a domicilio';
            }

         // Obtenemos el status
            if ($row->status == 1) {
               $status = "Pedido Confirmado";
               $status_pay = 'Pago Pendiente';
            }else if ($row->status == 3) {
               $status = "Pedido En Curso";
               $status_pay = 'Pago Pendiente';
            }else if ($row->status == 6) {
               $status = "Pedido Entregado";
               $status_pay = 'Pago completo';
            }else {
               $status = "Pedido Cancelado";
               $status_pay = "Pago no realizado";
            }

         // Obtenemos el repartidor
            if ($row->d_boy == 0) {
               $type_staff = 'Repartidor no asignado';
               $name_staff = 'Repartidor no asignado';
            }else {
               $staff         = Delivery::find($row->d_boy);
               $name_staff    = $staff->name;
               if ($staff->name == 0) {
                  $type_staff = "Repartidor de Chefitoo";
               }else {
                  $type_staff = "Repartidor de Comercio";
               }
            }

         // Costo de la compra
            if ($c_type == 1) { // Es %
               $commision = '%'.$c_value;
            }else { // es precio fijo
               $commision = '$'.$c_value;
            }

         // Monto de la comision
            if ($c_type == 1) {
               $amount_comm = ($row->total * $c_value) / 100;
            }else {
               $amount_comm = $c_value;
            }

         // Monto con el cupon aplicado
            $cup_amount = $row->total - $row->discount;
         // Costo de comision del envio

         // % o cantidad de envio por admin
            if (Admin::find(1)->c_type == 0) {
               $costs_ship = '$'.Admin::find(1)->c_value;
               $amount_ship = ($row->d_charges + Admin::find(1)->c_value);
            }else {
               $costs_ship = '%'.Admin::find(1)->c_value;
               $amount_ship = (($row->d_charges * Admin::find(1)->c_value) / 100);
            }


         // Tipo de pago
            if ($row->payment_method == 1) {
               $payment = "Efectivo";
            }elseif ($row->payment_method == 2) {
               $payment = "PayPal";
            }elseif ($row->payment_method == 3) {
               $payment = "Stripe";
            }else {
               $payment = 'Undefined';
            }
         // Cantidad de productos
         $item    = OrderItem::where('order_id',$row->id)->get();

         $items = OrderItem::where('order_item.id',$row->id)
         ->join('item','item.id','=','order_item.item_id')
         ->select('item.name','order_item.price','order_item.qty')->get();

         $allData[] = [
         'id'     => $row->id,
         'date'   => $row->created_at,//date('d-M-Y H:M:S',strtotime($row->created_at)),
         'city'   => isset($city->name) ? $city->name : null,
         'user'   => $row->user,
         'email'  => $user_email,
         'phone'  => $user_phone,
         'store'  => $row->store,
         'type'   => $type,
         'addr'   => $row->address,
         'status' => $status,
         'type_staff' => $type_staff,
         'name_staff' => $name_staff,
         'tot_prods'  => $item->count(),
         'amount' => $row->total,
         'comm_amount' => $amount_comm,
         'commision' => $commision,
         'cupon_value'  => $row->discount,
         'cupon_amount' => $cup_amount,
         'amount_ship'  => $amount_ship,
         'costs_ship'   => $costs_ship,
         'amount_send'  => $row->d_charges,
         'payment' => $payment,
         'pay_status' => $status_pay,
         'productos' => $items
         ];
      }

      return $allData;
   }

   public function getStatus($id)
   {
      $order = Order::find($id);

         if($order->status == 0)
         {
            $status = "<span class='badge badge-soft-danger badge-light'>Pendiente</span>";
         }
         elseif($order->status == 1)
         {
            $status = "<span class='badge badge-soft-info badge-light'>Confirmada</span>";
         }
         elseif($order->status == 2)
         {
            $status = "<span class='badge badge-soft-warning badge-light'>Cancelada</span>";
         }
         elseif($order->status == 3)
         {
            $status = "<span class='badge badge-soft-info badge-light'>Repartidor Asignado</span>";
         }
         elseif($order->status == 4)
         {
            $status = "<span class='badge badge-soft-info badge-light'>No encontrado en domiclio</span>";
         }
         else
         {
            $status = "<span class='badge badge-soft-success badge-light'>Entregado</span>";
         }

         return $status;
   }

   public function sendSms($id)
   {
      $order = Order::find($id);
      $admin = Admin::find(1);
      $comerce = User::find($order->store_id);
      $log = new Logs;
      if($order->status == 1)
      {

        if($order->type == 7) {
            $msg = "Hola! ".$order->name.", Tu orden #".$order->id." está lista para recoger 😃";
            $title = "Pedido Listo.";

            // Registramos el Log
            $dataLog = [
               'user_id'   => $order->user_id,
               'store_id'  => $order->store_id,
               'log'       => 'El Comercio '.$comerce->name.' Termino de preparar el pedido #'.$order->id,
               'view'      => 2
            ];
            $log->addNew($dataLog);
         }else {
            $msg = "Hola! ".$order->name.", 😁 Tu pedido #".$order->id." ha sido confirmado, El total a pagar es de ".$admin->currency.$order->total;
            $title = "Orden Confirmada";
         }

      }
      elseif ($order->status == 1.5) {

         $msg = "Hola! ".$order->name.", Estamos buscando un socio repartidor para tu pedido.";
         $title = "Buscando Repartidores.";
      }
      elseif($order->status == 2)
      {
         $msg   = "Hola! ".$order->name.", 😁 Tu orden #".$order->id." ha sido cancelada :( Lamentamos lo sucedido, porfavor contactanos si en algo podemos ayudarte.";
         $title = "Orden Cancelada";

      }
      elseif($order->status == 3)
      {
         $msg = "Hola ".$order->name.", 😁 se ha asignado un repartidor para tu pedido #".$order->id;
         $title = "Repartidor asignado.";
      }
       elseif($order->status == 4)
      {
         $msg = "No desesperes!! Tu Pedido #".$order->id." Esta en ruta!! 😃";
         $title = "Pedido en ruta";
      }
      elseif ($order->status == 5) {
         $msg = "🎉 Entregamos tu pedido🎉😃, ayudanos recomendandonos, no te olvides de calificar el comercio.";
         $title = "Pedido entregado";

         if($order->payment_method == 1) {
            $pay_type = "Pago En Efectivo";
         }else if ($order->payment_method == 2) {
            $pay_type = "Pago Via PayPal";
         }else {
            $pay_type = "Pago Via Stripe";
         }
         // Se envia notificacion al administrador
         $para       =   $order->email;
         $asunto     =   'Entregamos tu pedido';
         $mensaje    =   "Hemos entregado tu pedido<br />";
         $mensaje   .=   "<br />Recibo #".$order->id."<br /> <hr /><br />";
         $mensaje   .=   "Total de compra: ".$admin->currency.$order->total."<br />";
         $mensaje   .=   "Metodo de pago:".$pay_type;
         $mensaje   .=   "<br /><br /><hr> ayudanos recomendandonos, no te olvides de calificar el comercio y #QuedateEnCasa.";
         $cabeceras = 'From: KiiboGo' . "\r\n";

         $cabeceras .= 'MIME-Version: 1.0' . "\r\n";

         $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         @mail($para, $asunto, utf8_encode($mensaje), $cabeceras);

         // Registramos el Log

         if ($order->type == 7) {
            $logText = 'El pedido #'.$order->id.' ha sido entregado y el cliente paso a recoger.';
         }else {
            $logText = 'El pedido #'.$order->id.' ha sido entregado en el domicilio especificado.';
         }

         $dataLog = [
            'user_id'   => $order->user_id,
            'store_id'  => $order->store_id,
            'log'       => $logText,
            'view'      => 2
         ];
         $log->addNew($dataLog);
      }
      elseif ($order->status == 6) {
         $msg = "Hola! ".$order->name.", Tu orden #".$order->id." no pudo ser entregada 😒, comunicate con el comercio para más información!!";
         $title = "Pedido no entregado.";

         $dataLog = [
            'user_id'   => $order->user_id,
            'store_id'  => $order->store_id,
            'log'       => 'El pedido #'.$order->id.' ha sido cancelado.',
            'view'      => 2
         ];
         $log->addNew($dataLog);
      }

      if (isset($title)) {
         app('App\Http\Controllers\Controller')->sendPush($title,$msg,$order->user_id);
      }
      return true;
   }

   public function sendEmail($id)
   {
      $msg = "🎉 Entregamos tu pedido🎉😃, ayudanos recomendandonos, no te olvides de calificar el comercio.";
      $title = "Pedido entregado";
      $order = Order::find($id);
      $admin = Admin::find(1);
      $comerce = User::find($order->store_id);


      if($order->payment_method == 1) {
         $pay_type = "Pago En Efectivo";
      }else {
         $pay_type = "Medios electronios";
      }

      // Se envia notificacion al administrador
      $para       =   $order->email;

      $asunto     =   'Entregamos tu pedido';
     
      $mensaje    =   "Hemos entregado tu pedido<br />";
     
      $cabeceras = 'From: KiiboGo' . "\r\n";
      $cabeceras .= 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      @mail($para, $asunto, utf8_encode($mensaje), $cabeceras);

   }

   public function storeOrder($status = null)
   {
      $res = Order::where(function($query) use($status){

         if(isset($_GET['id']))
         {
            $query->where('orders.store_id',$_GET['id']);
         }

         if(isset($_GET['status']) && !$status)
         {
            if($_GET['status'] == 0)
            {
               $query->whereIn('orders.status',[0,1,1.5,3,4]);
            }
         }

         if($status == 6)
         {
            $query->where('orders.status',6);
         }

      })->orderBy('orders.id','DESC')
        ->get();

        $data   = [];
        $admin  = Admin::find(1);
        $item   = new OrderItem;

        foreach($res as $row)
        {


         $price_comm = $row->total- $row->d_charges;

          $data[] = [
            'id'       => $row->id,
            'name'     => $row->name,
            'phone'    => $row->phone,
            'address'  => $row->address,
            'lat_dest' => $row->lat,
            'lng_dest' => $row->lng,
            'lat_orig' => User::find($row->store_id)->lat,
            'lng_orig' => User::find($row->store_id)->lng,
            'status'   => $row->status,
            'd_boy'    => Delivery::find($row->d_boy),
            'total'    => $price_comm,//$row->total,
            'd_charges' => $row->d_charges,
            'real_total' => $item->RealTotal($row->id),
            'GetTaxes'   => $item->GetTaxes($row->id),
            'currency' => $admin->currency,
            'items'    => $item->getItem($row->id),
            'pay'      => $row->payment_method,
            'date'     => date('d-M-Y',strtotime($row->created_at)),
            'type'     => $row->type,
            'notes'    => $row->notes
          ];
        }

        return $data;
   }

   public function storeOrderAdmin($status = null)
   {
      $res = Order::where(function($query) use($status){

         if(isset($_GET['status']) && !$status)
         {
            if($_GET['status'] == 0)
            {
               $query->whereIn('orders.status',[0,1,1.5,3,4]);
            }
         }

         if($status == 5)
         {
            $query->where('orders.status',5);
         }

      })->orderBy('orders.id','DESC')
        ->get();

        $data   = [];
        $admin  = Admin::find($_GET['id']);
        $item   = new OrderItem;
        $i     = new OrderItem;

        foreach($res as $row)
        {

         $city_id = User::find($row->store_id)->city_id;
         
         if ($admin->city_notify == $city_id) {
            // Enviamos los pedidos de la ciudad del Administrador
            $data[] = [
               'store'    => User::find($row->store_id)->name,
               'id'       => $row->id,
               'name'     => $row->name,
               'phone'    => $row->phone,
               'address'  => $row->address,
               'status'   => $row->status,
               'd_boy'    => $row->d_boy,
               'tot_com'    => $row->total,
               'total'   => $i->RealTotal($row->id),
               'd_charges' => $row->d_charges,
               'currency' => $admin->currency,
               'items'    => $item->getItem($row->id),
               'pay'      => $row->payment_method,
               'date'     => date('d-M-Y',strtotime($row->created_at)),
               'type'     => $row->type,
               'notes'    => $row->notes
            ];
         }
        }

        return $data;
   }

   public function overView()
   {
      $total = Order::where('store_id',$_GET['id'])->count();
      $comp  = Order::where('store_id',$_GET['id'])->where('status',6)->count();

      return ['total' => $total,'complete' => $comp];
   }

   public function getUnit($id)
   {
      $item = Item::find($id);

      $data = [];

      if($item->small_price)
      {
         $data[] = ['id' => 1,'name' => "Small - Rs.".$item->small_price];
      }

      if($item->medium_price)
      {
         $data[] = ['id' => 2,'name' => "Medium - Rs.".$item->medium_price];
      }

      if($item->large_price)
      {
         $data[] = ['id' => 3,'name' => "Large/Full - Rs.".$item->large_price];
      }

      return $data;
   }

   public function editOrder($data,$id)
   {
         $order                     = $id > 0 ? Order::find($id) : new Order;
         $address                   = $data['address'];

         if($id == 0)
         {
            $check = AppUser::where('phone',$data['phone'])->first();

            if(isset($check->id))
            {
               $uid = $check->id;
            }
            else
            {
               $user              = new AppUser;
               $user->name        = isset($data['name']) ? $data['name'] : null;
               $user->phone       = isset($data['phone']) ? $data['phone'] : null;
               $user->store_id    = isset($data['store_id']) ? $data['store_id'] : null;//User::orderBy('id','DESC')->first()->id;
               $user->lat         = isset($data['lat']) ? $data['lat'] : null;
               $user->lng         = isset($data['lng']) ? $data['lng'] : null;
               $user->password    = 123456;
               $user->save();

               $uid = $user->id;
            }
         }

         $order->name               = isset($data['name']) ? $data['name'] : null;
         $order->phone              = isset($data['phone']) ? $data['phone'] : null;
         $order->store_id           = isset($data['store_id']) ? $data['store_id'] : null;//User::orderBy('id','DESC')->first()->id;
         $order->lat                = isset($data['lat']) ? $data['lat'] : null;
         $order->lng                = isset($data['lng']) ? $data['lng'] : null;
         $order->email              = "none";
         $order->address            = $address;

         if($id == 0)
         {
            $order->user_id         = $uid;
         }

         $order->status             = 1;
         $order->type               = 1;
         $order->d_charges          = 0;
         $order->discount           = 0;
         $order->total              = 0;
         $order->save();

         $item = new OrderItem;
         $item->editOrder($data,$order->id);

         $this->updateTotal($order->id);
   }

   public function updateTotal($id)
   {
      $order  = Order::find($id);
      $item   = new OrderItem;
      $total  = $item->getTotal($id);

      $d_charges = $this->getDelivery($total,$id);

      $total = $total + $d_charges;

      $order->total        = $total;
      $order->d_charges    = $d_charges;
      $order->save();
   }

   public function getDelivery($total,$id)
   {
      $order = Order::find($id);
      $user  = User::find($order->store_id);
      $val   = 0;

      if($user->delivery_charges_value > 0)
      {
         if($user->min_cart_value > 0)
         {
            if($total < $user->min_cart_value)
            {
               $val = $user->delivery_charges_value;
            }
         }
         else
         {
            $val = $user->delivery_charges_value;
         }
      }
      else
      {
         $val = 0;
      }

      return $val;
   }
}
