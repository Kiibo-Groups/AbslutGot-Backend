<?php

namespace App;

use App\Http\Controllers\NodejsServer;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;
class Commaned extends Authenticatable
{
    protected $table = "commaned";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [
            'address_origin' => 'required',
            'address_destin' => 'required',
            'd_charges' => 'required',
            'total' => 'required',
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

    public function addNew($data) 
    {
        $add                    = new commaned;
        $add->user_id           = isset($data['user_id']) ? $data['user_id'] : '';
        $add->address_origin    = isset($data['address_origin']) ? $data['address_origin'] : '';
        $add->lat_orig          = isset($data['lat_orig']) ? $data['lat_orig'] : 0;
        $add->lng_orig          = isset($data['lng_orig']) ? $data['lng_orig'] : 0;
        $add->address_destin    = isset($data['address_destin']) ? $data['address_destin'] : '';
        $add->lat_dest          = isset($data['lat_dest']) ? $data['lat_dest'] : 0;
        $add->lng_dest          = isset($data['lng_dest']) ? $data['lng_dest'] : 0;
        $add->first_instr       = isset($data['first_instr']) ? $data['first_instr'] : '';
        $add->second_instr      = isset($data['second_instr']) ? $data['second_instr'] : '';
        $add->d_boy             = isset($data['d_boy']) ? $data['d_boy'] : 0;
        $add->price_comm        = isset($data['price_comm']) ? $data['price_comm'] : 0;
        $add->d_charges         = isset($data['d_charges']) ? $data['d_charges'] : 0;
        $add->propine           = isset($data['propina']) ? $data['propina'] : 0;
        $add->add_cash          = isset($data['add_cash']) ? $data['add_cash'] : 0;
        $add->total             = isset($data['total']) ? $data['total'] : 0;
        $add->payment_method    = isset($data['payment_method']) ? $data['payment_method'] : 1;
        $add->payment_id        = isset($data['payment_id']) ? $data['payment_id'] : 0;
        $add->status            = isset($data['status']) ? $data['status'] : 0;

        /** Generamos Codigo de entrega */
        $key = '';
        $pattern = $add->id.'1234567890'.$data['address_origin'];
        $key = substr(md5($pattern),0,6);
        // Guardamos
        $add->code_order = strtoupper($key);
        /** Generamos Codigo de entrega */

        $add->save();

        /** Agregamos el servicio a Firebase */
                
            $us = User::find($add->user_id);

            $return = array(
                'id'        => $add->id,
                'user'      => isset($us) ? $us->name : 'Anonimo',
                'user_id'   => isset($us) ? $add->user_id : 0,
                'origen'    => [
                    'address' => $add->address_origin,
                    'lat'     => $add->lat_orig,
                    'lng'     => $add->lng_orig,
                    'instr'   => $add->first_instr
                ],
                'destino'   => [
                    'address' => $add->address_destin,
                    'lat'     => $add->lat_dest,
                    'lng'     => $add->lng_dest,
                    'instr'   => $add->second_instr
                ],
                'propina'     => $add->propine,
                'total'       => $add->total,    
                'd_charges'   => $add->d_charges,
                'status'      => 0
            );

            $server_fb = new NodejsServer;
            $addServer = $server_fb->newOrder($return);
            $add->external_id = $addServer['data'];
            $add->save();
        /** Agregamos el servicio a Firebase */

        if (isset($data['order_store']) && $data['order_store'] == true) {
            // Es un pedido directo a negocio, Solicitamos link para Whatsapp            
            $url_wt = $this->addNewWhatsapp($data,$add);
            return ['data' => 'done','url' => $url_wt];
        }else {
            // Comenzamos la solicitud de repartidores
                $req = new NodejsServer;
                $data = [
                    'id_order' => $add->id
                ];
                
                $req->NewOrderComm($data);

            // Retornamos hecho
            return ['data' => 'done'];
        }
    }

    public function addNewWhatsapp($data,$add)
   {

    
        // Obtenemos el comercio
        $store = User::find($data['store_id']);

        // Obtenemos al Usuario
        $user  = AppUser::find($add->user_id);
        
        $dnl = "\r\n";
        $ddnl = "\n\n";
        $nl="\n";
        $tabSpace="      ";

        /******* Fecha y Tipo de servicio ***********/
        $msg = 'Nuevo Pedido #'.$add->id.$dnl;
        $msg .= "Fecha ".date('d-M-Y',strtotime($add->created_at))." | ".date('h:i:A',strtotime($add->created_at)).$ddnl;

        $msg .= "Hola, Vengo de Prommo.".$ddnl;

        $msg .= "* Tipo de servicio: ";
        $msg .= " Servicio a domicilio".$dnl;

        $msg .= $nl;
        /******* Fecha y Tipo de servicio ***********/

        
        /******* Datos y direccion ***********/
        $msg .= "Nombre: ".$user->name.$dnl;
        $msg .= "Telefono: ".$user->phone.$dnl;
        $msg .= "Dirección: ".$add->address_destin.$dnl;
        /******* Datos y direccion ***********/

        $msg .= $nl;
        $msg .= $nl;
        
        /******* Instrucciones ***********/
        $msg .= "Instrucciones: ".$nl;
        $msg .= $data['first_instr'].$nl;

        $msg .= $nl;
        $msg .= $nl;
        $msg .= $nl;
        /******* Instrucciones ***********/

        /******* Link para el negocio *********/
        $msg .= "NOTA.- Link exclusivo para el negocio: ";
        $msg .= $nl;

        $msg .= "Ingresa aqui para validar cuando tu pedido este listo.";
        $msg .= $nl;
        $msg .= "https://validate-order.prommo.com";
        /******* Link para el negocio *********/

        // Quitamos espacios del telefiono
        $phone = str_replace(' ','',$store->phone);
        $phone = str_replace('-','',$store->phone);
        $phone = str_replace('+','',$store->phone);

        $url = 'https://wa.me/+521'.$phone.'?text='.urlencode($msg);

        return  $url;
      
   }


    public function getIva($costs_ship)
    {
        $admin = Admin::find(1);

        $iva_amount      = 0;
        $iva_amount_type = $admin->iva_type; // Cargos de iva de la plataforma
        $iva_amount_value = $admin->iva_value; // Cargos de iva de la plataforma
        // Comision + IVA 
        if ($iva_amount_type == 0) { // Valor en %
            $iva_amount = ($costs_ship * $iva_amount_value) / 100;
        }

        return $iva_amount;
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($status)
    {
        return commaned::where(function($query) use($status){

            if ($status == 1) {
                $query->whereIn('commaned.status',[1,4.5]);
            }else {
                $query->where('commaned.status',$status);
            }

        })->leftjoin('app_user','app_user.id','=','commaned.user_id')
            ->select('app_user.name as name_user','app_user.*','commaned.*')
            ->orderBy('commaned.id','DESC')->get();
    }


    public function getElement($id)
    {
        return commaned::where('commaned.id',$id)
            ->leftjoin('app_user','app_user.id','=','commaned.user_id')
            ->select('app_user.name as name_user','app_user.*','commaned.*')
            ->orderBy('commaned.id','DESC')->first();
    }
    
    public function viewDboyComm($id)
    {
        $comm = Commaned::find($id);

        if ($comm->d_boy > 0) {
            $dboy = Delivery::find($comm->d_boy);
            if ($dboy) {
                return $dboy->name;
            }else {
                return 'No encontrado';
            }
        }else {
            return "Sin asignar";
        }
    }

    public function viewUserComm($id)
    {
        $comm = Commaned::find($id);

        if ($comm->user_id > 0) {
            $user = AppUser::find($comm->user_id);
            if ($user) {
                return $user->name;
            }else {
                return 'No encontrado';
            }
        }else {
            return "No Encontrado";
        }
    }

    /**
     * 
     * Obtenemos costos de envio por repartos
     * 
    */

    function Costs_shipKM($data)
    {
        
        $admin = Admin::find(1);
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=kilometers&origins=".$data['lat_orig'].",".$data['lng_orig']."&destinations=".$data['lat_dest'].",".$data['lng_dest']."&key=".$admin->ApiKey_google;

        // Ciudad de servicio
        $charge_delivy = (isset($data['city_id'])) ? City::find($data['city_id']) : Admin::find(1); 

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);


        $request = json_decode($output, true);

        $max_distance_staff = $admin->max_distance_staff; // Maxima distancia de notificacion para repartidores
        $min_distance       = $charge_delivy->min_distance; // Distancia minima del servicio
        $type_value         = $charge_delivy->c_type; // Tipo del valor KM/Fijo
        $value              = $charge_delivy->c_value; // Valor de la comision
        $min_value          = $charge_delivy->min_value; // Valor por el minimo del servicio
        $distance = 0; // Distancia de un punto a otro
        $service = 0; // Status del servicio
        $costs_ship = 0; // Costos de envio
        $times_delivery = '0 mins'; // Tiempos de entrega
        $times_delivery_text = "0 mins";
        $service_fee = 0;
        // Validamos si es un servicio normal o desde un negocio
        $order_store    = isset($data['order_store']) ? $data['order_store'] : false;
        $store_id       = isset($data['store_id']) ? $data['store_id'] : 0;

        if ($order_store) { // TRUE => el pedido es a un negocio en especifico
            /** Obtenemos las comisiones del negocio */
            $store      = User::find($store_id);
            if ($store) {
                $t_value    = $store->t_value;
                $t_type     = $store->t_type;
            }else {
                $t_value    = $admin->t_value_comm;
                $t_type     = $admin->t_type_comm;    
            }
        }else { // FALSE => el pedido es de un punto A to B
            $t_value    = $admin->t_value_comm;
            $t_type     = $admin->t_type_comm;
        }

        if ($request['status'] == 'OK') {
            if($request['rows'][0]['elements'][0]['status'] == 'OK') {
                $km_inm = $request['rows'][0]['elements'][0]['distance']['value'];
                $times_delivery_text = $request['rows'][0]['elements'][0]['duration']['text'];
                $times_delivery      = round($request['rows'][0]['elements'][0]['duration']['value'] / 60,2); // Convertimos a minutos

                $distance = ($km_inm / 1000); // lo convertimos a decimales
           
                if ($distance < $max_distance_staff) { // si hay servicio
                    $service = 1; // Si hay servicio
                    
                    if (round($distance,2) < $min_distance) { // si los km extra son menor a 0 se cobra el minimo por servicio
                        $costs_ship = $min_value;
                    }else {
                        $km_extra   = ($distance - $min_distance); // 1.558 - 1 =  0.558
                        $value_ext  = ($type_value == 0) ? ($km_extra * $value) : ($km_extra + $value); // -1.442 * 10
                        $costs_ship = ($min_value + $value_ext); // 20 + 
                    }

                    // Calculamos la tarifa del servicio
                    if ($t_type == 0) { // Valor Fijo
                        $service_fee = $t_value;
                    }else { // Valor en %
                        $service_fee = ($costs_ship * $t_value) / 100;
                    }
                } // no hay servicio a esta distancia
            }
        }
        
        return [ 
            'service'      => $service,
            'costs_ship'    => round($costs_ship,2),
            'duration'      => $times_delivery_text,
            'distance'      => round($distance,2),
            'service_fee'   => round($service_fee,2),
            'total_amount'  => round($costs_ship + $service_fee,2)
        ];
    }

    /**
     * 
     * Obtenemos listado de repartidores mas cercanos 
     * 
    */
    public function getNearby($event_id)
    {
        // Obtenemos el arreglo de los repartidores
        $staff       = Delivery::where('store_id',0) // que sea del admin
                    ->where('status',0) // que este activo
                    ->where('status_admin',0) // que no este bloqueado
                    ->get();      
        // Obtenemos las coordenadas de entrega
        $order       = Commaned::find($event_id);
        
        // Seteamos el mensaje
        $msg2 = "Nuevo servicio de reparto, Ingresa para más información";
        
        $data  = [];
        foreach ($staff as $key) {
            // Obtenemos lat & lng de cada repa
            $lat = $key->lat;
            $lon = $key->lng;

            // Verificamos que esten bien
            if ($lat != null || $lat !='' && $lot != null || $lon !='') {        
                $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=kilometers&origins=".$lat.",".$lon."&destinations=".$order->lat_orig.",".$order->lng_orig."&key=".Admin::find(1)->ApiKey_google;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
        
        
                $request = json_decode($output, true);
                
                $max_distance = 0;
                $max      = 0;
                if ($request['status'] == 'OK') {
                    if ($request['rows'][0]['elements'][0]['status'] == 'OK') {
                        $km_inm = intval(str_replace('km','',$request['rows'][0]['elements'][0]['distance']['value']));
                        
                        $max_distance = round($km_inm / 1000,2);
                        
                        //Obtenemos la distancia de cada repa al punto A
                        $distancia_total = $max_distance;
                        // Si la distancia maxima del repa es mayor a 0 procedemos 
                        if ($key->max_range_km > 0) {
                            // Si la distancia maxima de entrega entra en el rango entramos
                            if ($distancia_total <= $key->max_range_km) {
                                
                                if ($key->amount_acum > 0) { // Filtramos a repartidores que tengan saldo positivo
                                    $data[] = [
                                        'max_range_km' => $key->max_range_km,
                                        'distancia_total' => $distancia_total,
                                        'km_inm' => $km_inm,
                                        'dboy' => $key->id,
                                        'name' => $key->name,
                                        'request' => $request
                                    ];
                                }   
                            };
                        };
                    }
                    
                }
            } 
        }
 
        if (count($data) > 0) {
            return [
                'dboys' => $this->ORDER_ASC_STAFF($data)
            ];
        }else {
            return [
                'dboys' => $data
            ];
        }
    }

    function ORDER_ASC_STAFF($data)
    {
        foreach ($data as $key => $row) {
            $aux[$key] = $row['distancia_total'];
        }

        array_multisort($aux, SORT_ASC, $data);

        return $data;
    }

    /**
     * 
     * Seteamos el repartidor enviado por el servidor NODEJS 
     * 
    */

    function setStaffEvent($event_id,$dboy_id)
    {
        
        // Checamos si el pedido ya fue tomado
        $event = Commaned::find($event_id);

        if ($event->d_boy != 0) {
            return [
                'status' => 'in_rute'
            ];
        }else if ($event->status == 2) { // el pedido fue cancelado
            return [
                'status' => 'cancel'
            ];
        }else {
            // Seteamos la tabla
            Order_staff::where('event_id',$event_id)->delete();

            // Guardamos el Nuevo
            $order = new Order_staff;

            $order->external_id = $event->external_id;
            $order->event_id = $event_id;
            $order->d_boy    = $dboy_id;
            $order->type     = 1; // 0 = Food Delivery & 1 = Delivery Box
            $order->status   = 0;
            $order->save();
 
            // Notificamos al repartidor
			app('App\Http\Controllers\Controller')->sendPushD("Nuevo servicio","Nueva solicitud de reparto, revisa los detalles.",$dboy_id);

            return [
                'status' => 'not_rute',
                'external_id'  => $order->external_id
            ];
        }
    }

    /**
     * 
     * Eliminamos al no tener respuesta de algun repartidor 
     * 
    */

    function delStaffEvent($event_id)
    {
        // Seteamos la tabla
        Order_staff::where('event_id',$event_id)->delete();

        // Marcamos como repartidor no encontrado status = 3
        $event = Commaned::find($event_id);
        
        if ($event->status == 0) {
            $event->status = 3;
            $event->save();   
            // Notificamos al negocio que no se encontraron repartidores
            $msg = "No hemos encontrado un repartidor disponible para tu solicitud, por favor vuelve a intentarlo";
            $title = "No encontramos repartidores!!";
            app('App\Http\Controllers\Controller')->sendPush($title,$msg,$event->user_id);
        }else if ($event->status == 2) { // ya se habia cancelado
            // Notificamos al negocio que no se encontraron repartidores
            $msg = "Lamentamos que tuvieras que cancelar, te invitamos a probar nuevamente nuestro servicio y/o comunicate con nosotros en caso de algun problema.";
            $title = "Solicitud cancelada!!";
            app('App\Http\Controllers\Controller')->sendPush($title,$msg,$event->user_id);
        }
        
        return [
            'status' => 'done'
        ];
    }

    /**
     * 
     * Obtenemos el historial completo 
     * 
    */

    public function history($id)
    {
       $data     = [];
       $currency = Admin::find(1)->currency;
 
       $orders = Commaned::where(function($query) use($id){
 
          if($id > 0)
          {
             $query->where('commaned.user_id',$id);
          }
 
          if(isset($_GET['status']))
          {
             if($_GET['status'] == 3 || $_GET['status'] == 3.5)
             {
                $query->whereIn('commaned.status',[3,3.5,4]);
             }
             else
             {
                $query->where('commaned.status',5);
             }
          }
 
       })->join('delivery_boys','commaned.d_boy','=','delivery_boys.id')
          ->select('commaned.*','delivery_boys.name as dboy')
          ->orderBy('id','DESC')
          ->get();
 
       
       foreach($orders as $order)
       {
          
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
            $status = "Repartidor no encontrado";
          }
          elseif($order->status == 4)
          {
            $status = "Elegido para entregar por ".$order->dboy;
          }
          elseif($order->status == 5)
          {
            $status = "Pedido entregado";
          }
          elseif($order->status == 6)
          {
            $status = "Pedido entregado";
          }
          else
          {
             $status = "Sin estatus";
          }
 
          $countRate = Rate::where('event_id',$order->id)->where('user_id',$id)->first();
          $tot_com   = $order->total - $order->d_charges;
 
          $data[] = [
 
             'id'        => $order->id,
             'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
             'total'     => round($order->total,2),
             'tot_com'   => round($tot_com,2), 
             'd_charges' => round($order->d_charges,2),
             'status'    => $status,
             'st'        => $order->status,
             'hasRating' => isset($countRate->id) ? $countRate->star : 0,
             'ratStaff'  => isset($countRate->staff_id) ? $countRate->staff_id : 0,
             'event'      => $order,
             'pay'       => $order->payment_method
          ];
       }
 
       return $data;
    }

    public function history_staff($id)
    {
       $data     = [];
       $currency = Admin::find(1)->currency;
 
       $orders = Commaned::where(function($query) use($id){
 
          if(isset($_GET['id']))
          {
             $query->where('commaned.d_boy',$_GET['id']);
          }
 
          $query->whereIn('commaned.status',[0,1,2,3,4,4.5,5,6]);
 
       })->join('delivery_boys','commaned.d_boy','=','delivery_boys.id')
          ->select('commaned.*','delivery_boys.name as dboy')
          ->orderBy('id','DESC')
          ->get();
 
       
       foreach($orders as $order)
       {
          
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
            $status = "Repartidor no encontrado";
          }
          elseif($order->status == 4)
          {
            $status = "Elegido para entregar por ".$order->dboy;
          }
          elseif($order->status == 5)
          {
            $status = "Pedido entregado";
          }
          elseif($order->status == 6)
          {
            $status = "Pedido entregado";
          }
          else
          {
             $status = "Sin estatus";
          }
 
          $countRate = Rate::where('event_id',$order->id)->where('staff_id',$_GET['id'])->first();
          $tot_com   = $order->total - $order->d_charges;
 
          $data[] = [
 
             'id'        => $order->id,
             'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
             'total'     => $order->total,
             'tot_com'   => $tot_com, 
             'd_charges' => $order->d_charges,
             'status'    => $status,
             'st'        => $order->status,
             'hasRating' => isset($countRate->id) ? $countRate->star : 0,
             'ratStaff'  => isset($countRate->staff_id) ? $countRate->staff_id : 0,
             'event'      => $order,
             'pay'       => $order->payment_method
          ];
       }
 
       return $data;
    }

    /**
     * 
     * Obtenemos todos los eventos de este usuario que esten activos 
     * 
    */
    function chkEvents_comm($id)
    {
        $req = commaned::where(function($query) use($id){

            $query->where('commaned.user_id',$id);
            $query->whereIn('commaned.status',[0,1,3,4,4.5,5]);
        })->orderBy('id','DESC')
        ->get();
        
        $data = [];

        foreach ($req as $key) {
            
            $data[] = [
                'dboy' => ($key->d_boy != 0) ? Delivery::find($key->d_boy) : [],
                'event' => $key
            ];
        }

        return $data;
    }

    /**
     * 
     * Cancelamos el pedido por parte del usuario 
     * 
    */

    function cancelComm_event($event_id)
    {
        
        $req = Commaned::find($event_id);

        $req->status = 2;
        $req->save();

        // Seteamos la tabla
        Order_staff::where('event_id',$event_id)->delete();

        return [
            'status' => 'done'
        ];
        
    }

    /**
     * 
     * Calificamos el servicio 
     * 
    */

    function rateComm_event($data)
    {
        $add = new Rate;
        // Agregamos nuevo
        if (isset($data['user_id'])) {
            $add->user_id     = $data['user_id'];
            $add->staff_id    = $data['d_boy'];
            $add->event_id    = $data['oid'];
            $add->star        = $data['star'];
            $add->comment_staff     = isset($data['comment']) ? $data['comment'] : '';
            $add->sanit_process = isset($data['covid_prevention']) ? 1 : 0;
            $add->status_prod = isset($data['covid_prevention_product']) ? 1 : 0;
            
            $add->save();

            // Marcamos como calificado
            $req = commaned::find($data['oid']);

            $req->status = 6;
            $req->save();

            $fb_server = new NodejsServer;
			$dat_s = array(
				'external_id' 	=> $req->external_id,
				'status' 		=> $req->status,
				'change_from'   => 'user_app'
			);
			$fb_server->orderStatus($dat_s); 

            // Notificamos
            $msg = "El usuario ha calificado tu servicio con ".$data['star'].' estrellas.';
            $title = "Te han calificado por tu servicio.";
            app('App\Http\Controllers\Controller')->sendPushD($title,$msg,$data['d_boy']);
            
            return ['data' => true];
        }else {
            $add->staff_id    = $data['d_boy'];
            $add->event_id    = $data['oid'];
            $add->star        = $data['star'];
            $add->comment_staff     = isset($data['comment']) ? $data['comment'] : '';
            $add->sanit_process = isset($data['covid_prevention']) ? 1 : 0;
            $add->status_prod   = isset($data['covid_prevention_product']) ? $data['covid_prevention_product'] : 0;
            $add->save();           
            
            
            return ['data' => true];
        }
    }

    /**
     * 
     * Obtenemos Reporte de Servicios 
     * 
    */

    public function getReport($data)
    {
       $res = Commaned::where(function($query) use($data) {
 
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
             $query->whereDate('commaned.created_at','>=',$from);
          }
 
          if($to)
          {
             $query->whereDate('commaned.created_at','<=',$to);
          }
 
       })->orderBy('commaned.id','ASC')->get();
 
       $allData = [];
 
       foreach($res as $row)
       {
 
            // ID
            // Usuario
            // Email
            // Repartidor
            // Origen
            // Destino
            // Cargos de envio
            // Cargos de IVA
            // Total
            // Metodo de pago
            // Imagen de entrega
            // Estatus del pedido.

            // Obtenemos el usuario
            $user = User::find($row->user_id);

            // Obtenemos el repartidor
            $staff = Delivery::find($row->d_boy);

            $allData[] = [
                'id'     => $row->id,
                'date'   => $row->created_at,
                'user'   => isset($user) ? $user->name : 'Indefinido.',
                'email'  => isset($user) ? $user->email : 'Indefinido.',
                'staff'  => isset($staff) ? $staff->name : 'Indefinido',
                'origin' => isset($row->address_origin) ? $row->address_origin : 'Indefinido',
                'destin' => isset($row->address_destin) ? $row->address_destin : 'Indefinido',
                'd_charges' => $row->d_charges,
                'iva_charges' => $row->iva_charges,
                'total'  => $row->total,
                'payment_method' => $row->payment_method,
                'pic_order' => Asset('upload/order/delivery/'.$row->pic_end_order),
                'status' => $row->status
            ];
       }
 
       return $allData;
    }
}
