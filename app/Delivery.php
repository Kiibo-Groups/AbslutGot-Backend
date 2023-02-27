<?php

namespace App;


use App\Http\Controllers\NodejsServer;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;
class Delivery extends Authenticatable
{
    protected $table = "delivery_boys";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */

    public function rules($type)
    {
        if($type === 'add')
        {
            return [
                'phone' => 'required|unique:delivery_boys',
            ];
        }
        else
        {
            return [
                'phone'     => 'required|unique:delivery_boys,phone,'.$type,
            ];
        }
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

    public function addNew($data,$type,$from)
    {


        $add                    = $type === 'add' ? new Delivery : Delivery::find($type);
        if (isset($data['deliveryVia']) && $data['deliveryVia'] == 'user') { // Negocio
            $add->city_id           = User::find(Auth::user()->id)->city_id;
            $add->store_id          = Auth::user()->id;
        }else { // Admin
            $add->city_id           = isset($data['city_id']) ? $data['city_id'] : 0;
            $add->store_id          = 0;
        }

        $add->name              = isset($data['name']) ? $data['name'] : '';
        $add->phone             = isset($data['phone']) ? $data['phone'] : '';
        $add->c_type_staff      = isset($data['c_type_staff']) ? $data['c_type_staff'] : '';
        $add->c_value_staff     = isset($data['c_value_staff']) ? $data['c_value_staff'] : '';
        $add->type_driver       = isset($data['type_driver']) ? $data['type_driver'] : 0;
        $add->max_range_km      = isset($data['max_range_km']) ? $data['max_range_km'] : 1;
        $add->rfc               = isset($data['rfc']) ? $data['rfc'] : '';

        if ($from == 'app') {
            $add->status = 1; // Bloqueado
            $add->status_admin = 1; // Bloqueado
        }else {
            $add->status            = isset($data['status']) ? $data['status'] : 0;
            $add->status_admin            = isset($data['status_admin']) ? $data['status_admin'] : 0;
        }

        if(isset($data['password']))
        {
            $add->password      = bcrypt($data['password']);
            $add->shw_password  = $data['password'];
        }

        $add->save();
        // Registramos en el servidor Secundario
       try {
            $addServer = new NodejsServer;
            $return = array(
                'id'        => $add->id,
                'city_id'   => $add->city_id,
                'name'      => $add->name,
                'phone'     => $add->phone,
                'type_driver' => $add->type_driver,
                'max_range_km' => $add->max_range_km,
                'external_id'   => $add->external_id,
                'status'        => $add->status,
                'status_admin'  => $add->status_admin,
            );
            
            if ($type == 'add') {
                $addServer->newStaffDelivery($return);
            }else {
                $addServer->updateStaffDelivery($return);
            }

            if ($from == 'app') {
                return ['msg' => 'done','user_id' => $add->id, 'external_id' => $add->external_id]; 
            }
       }catch (\Throwable $th) {
        if ($from == 'app') {
            return ['msg' => 'fail']; 
        }
       }
    }

    /*
    |--------------------------------------
    |Validate Signup from app
    |--------------------------------------
    */
    public function ValidateAppSign($data)
    {
        $view = Delivery::where('phone',$data['phone'])->first();

        if ($view) {
            return ['msg' => 'phoneinuse']; 
        }

        return ['msg' => 'done'];
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($store = 0)
    {
        return Delivery::where(function($query) use($store) {

            if($store > 0)
            {
                $query->where('store_id',$store);
            }

        })->leftjoin('users','delivery_boys.store_id','=','users.id')
          ->leftjoin('city','delivery_boys.city_id','=','city.id')
          ->select('city.name as city','delivery_boys.*')
          ->orderBy('delivery_boys.id','DESC')->get();
    }

    /*
    |--------------------------------------
    |Login To
    |--------------------------------------
    */
    public function login($data)
    {
     $chk = Delivery::where('status_admin',0)->where('phone',$data['phone'])->where('shw_password',$data['password'])->first();

     if(isset($chk->id))
     {
        return [
            'msg' => 'done',
            'user_id' => $chk->id,
            'external_id' => $chk->external_id,
            'user_type' => $chk->store_id
        ];
     }
     else
     {
        return ['msg' => 'Opps! Detalles de acceso incorrectos'];
     }
    }

    /*
    |--------------------------------------
    |Get Report
    |--------------------------------------
    */
    public function getReport($data)
    {
        $res = Delivery::where(function($query) use($data) {

            if($data['staff_id'])
            {
                $query->where('delivery_boys.id',$data['staff_id']);
            }

        })->join('orders','delivery_boys.id','=','orders.d_boy')
        ->select('orders.store_id as ord_store_id','orders.*','delivery_boys.*')
        ->orderBy('delivery_boys.id','ASC')->get();

       $allData = [];

       foreach($res as $row)
       {

            // Obtenemos el comercio
            $store = User::find($row->ord_store_id);

            $allData[] = [
                'id'                => $row->id,
                'name'              => $row->name,
                'rfc'               => $row->rfc,
                'email'             => $row->email,
                'store'             => isset($store->name) ? $store->name : 'No identificado',
                'store_rfc'         => isset($store->rfc) ? $store->rfc : 'No identificado',
                'platform_porcent'  => $row->t_charges,
                'type_staff_porcent'=> ($row->c_type_staff == 0) ? 'Valor Fijo' : 'valor en %',
                'staff_porcent'     => $row->c_value_staff,
                'total'             => $row->total
            ];
       }

       return $allData;
    }

    /*
    |--------------------------------------
    |Get all data from db for Charts
    |--------------------------------------
    */
    public function overView()
    {
        // 

        $admin = new Admin;

        return [
            'total'     => Order::where('d_boy',$_GET['id'])->count(),
            'complete'  => Order::where('d_boy',$_GET['id'])->where('status',6)->count(),
            'canceled'  => Order::where('d_boy',$_GET['id'])->where('status',2)->count(),
            'saldos'    => $this->saldos($_GET['id']),
            'x_day'     => [
                'tot_orders' => Order::where('d_boy',$_GET['id'])->whereDate('created_at','LIKE','%'.date('m-d').'%')->count(),
                'amount'     => $this->chartxday($_GET['id'],0,1)['amount']
            ],
            'day_data'     => [
                'day_1'    => [
                'data'  => $this->chartxday($_GET['id'],2,1),
                'day'   => $admin->getDayName(2)
                ],
                'day_2'    => [
                'data'  => $this->chartxday($_GET['id'],1,1),
                'day'   => $admin->getDayName(1)
                ],
                'day_3'    => [
                'data'  => $this->chartxday($_GET['id'],0,1),
                'day'   => $admin->getDayName(0)
                ]
            ],
            'week_data' => [
                'total' => $this->chartxWeek($_GET['id'])['total'],
                'amount' => $this->chartxWeek($_GET['id'])['amount']
            ],
            'month'     => [
                'month_1'     => $admin->getMonthName(2),
                'month_2'     => $admin->getMonthName(1),
                'month_3'     => $admin->getMonthName(0),
            ],
            'complet'   => [
                'complet_1'    => $this->chart($_GET['id'],2,1)['order'],
                'complet_2'    => $this->chart($_GET['id'],1,1)['order'],
                'complet_3'    => $this->chart($_GET['id'],0,1)['order'],
            ],
            'cancel'   => [
                'cancel_1'    => $this->chart($_GET['id'],2,1)['cancel'],
                'cancel_2'    => $this->chart($_GET['id'],1,1)['cancel'],
                'cancel_3'    => $this->chart($_GET['id'],0,1)['cancel']
            ]
        ];
    }

    public function saldos($id)
    {
        // Saldos y Movimientos
        $discount = 0;
        $cargos   = 0;
        $ventas   = 0;
        $comm     = 0;
        
        $i          = new OrderItem;
        $staff      = Delivery::find($id);
        $saldo      = $staff->amount_acum;
        $order_day  = Order::where(function($query) use($id){

            $query->where('d_boy',$id);

        })->where('status',6)->get();

        $sum   = Order::where(function($query) use($id){

            $query->where('d_boy',$id);

        })->where('status',6)->sum('d_charges');

        if ($order_day->count() > 0) {
            $comm   = ($sum * $staff->c_value_staff) / 100;
            $ventas = $ventas + ($sum - $comm);
            $cargos = $cargos + $comm;
        }

        return [
            'Saldo'      => $saldo,
            'cargos'     => $cargos,
            'ventas'     => $ventas
        ];
    }

    public function chart($id,$type,$sid = 0)
    {
        $month      = date('Y-m',strtotime(date('Y-m').' - '.$type.' month'));

            $order   = Order::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

            })->where('status',6)->whereDate('created_at','LIKE',$month.'%')->count();


            $cancel  = Order::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

            })->where('status',2)->whereDate('created_at','LIKE',$month.'%')->count();

            return ['order' => $order,'cancel' => $cancel];
    }

    public function chartxday($id,$type,$sid = 0)
    {
        $admin = new Admin;
        $date_past = strtotime('-'.$type.' day', strtotime(date('Y-m-d')));
        $day = date('m-d', $date_past);

        $comm = 0;
        $amount = 0;
        $debt  = 0 ;
        $ventas = 0;

        $order   = Order::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

        })->where('status',6)->whereDate('created_at','LIKE','%'.$day.'%')->count();


        $cancel  = Order::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

        })->where('status',2)->whereDate('created_at','LIKE','%'.$day.'%')->count();


        if ($type == 0) {
            $i              = new OrderItem;
            $staff          = Delivery::find($id);
           
            $sum   = Order::where(function($query) use($id){

                $query->where('d_boy',$id);

            })->where('status',6)
                ->whereDate('created_at','LIKE','%'.$day.'%')->sum('d_charges');

            
            $comm = ($sum * $staff->c_value_staff) / 100;
            $ventas = $ventas + ($sum - $comm);
        }

        return [
            'order' => $order,
            'cancel' => $cancel,
            'amount' => $ventas
        ];
    }

    public function chartxWeek($id)
    {
            $date = strtotime(date("Y-m-d"));
            $ventas = 0;
            $init_week = strtotime('last Sunday');
            $end_week  = strtotime('next Saturday');

            $total   = Order::where(function($query) use($id){

                $query->where('d_boy',$id);

            })->where('status',6)
                ->where('created_at','>=',date('Y-m-d', $init_week))
                ->where('created_at','<=',date('Y-m-d', $end_week))->count();

            $sum   = Order::where(function($query) use($id){

                $query->where('d_boy',$id);

            })->where('status',6)
                ->where('created_at','>=',date('Y-m-d', $init_week))
                ->where('created_at','<=',date('Y-m-d', $end_week))->sum('d_charges');

            $dboy = Delivery::find($id);

            $comm = ($sum * $dboy->c_value_staff) / 100;
            $ventas = $ventas + ($sum - $comm);

            return [
                'total'   => $total,
                'amount'  => $ventas,
                'lastday' => date('Y-m-d', $init_week),
                'nextday' => date('Y-m-d', $end_week)
            ];
    }

    /*
    |--------------------------------------
    |Add Comm
    |--------------------------------------
    */

    public function add_comm($data,$id)
    {
        $staff = Delivery::find($id);
        $acum  = $staff->amount_acum + $data['pay_staff'];
        $staff->amount_acum = $acum;
        $staff->save();
        return true;
        
    }

    public function Commset_delivery($order_id,$d_boy_id)
    {   
        $order          = Order::find($order_id);
        $staff          = Delivery::find($d_boy_id);
        $payment_method = $order->payment_method; // tipo de pago 1 Efectivo
        $c_value_staff  = $staff->c_value_staff; // 18
        
        $delivery_charges = $order->d_charges; // 43
       
        $comm_admin   = ($delivery_charges * $c_value_staff) / 100; // = 7.74 - Ganancia del admin
        $comm_repa    = ($delivery_charges - $comm_admin); // = 35.26 - Ganancia del repa
        
        /*
        * si payment == 1 el pago fue en efectivo y el repartidor le debe al admin
        * si payment == 2 el pago fue con tarjeta y el administrador le debe al repartidor
        */
        
        if ($payment_method == 1) {
            $newSaldo = ($staff->amount_acum - $comm_admin);
        }else {
            $newSaldo = ($staff->amount_acum + $comm_repa);
        }

        $staff->amount_acum = $newSaldo;
        $staff->save();
 
        return true;
    }

    public function Commset_delivery_comm($event_id, $d_boy_id)
    {
        $order          = Commaned::find($event_id);
        $staff          = Delivery::find($d_boy_id);
        $payment_method = $order->payment_method; // tipo de pago 1 Efectivo
        $c_value_staff  = $staff->c_value_staff; // 18
        
        $delivery_charges = $order->d_charges; // 39.05
       
        $comm_admin   = ($delivery_charges * $c_value_staff) / 100; // = 7.029 - Ganancia del admin
        $comm_repa    = ($delivery_charges - $comm_admin); // = 32.021 - Ganancia del repa

        /*
        * si payment == 1 el pago fue en efectivo y el repartidor le debe al admin
        * si payment == 2 el pago fue con tarjeta y el administrador le debe al repartidor
        */
        
        if ($payment_method == 1) {
            $newSaldo = ($staff->amount_acum - $comm_admin);
        }else {
            $newSaldo = ($staff->amount_acum + $comm_repa);
        }

        
        $staff->amount_acum = $newSaldo;
        $staff->save();

        return true;
    }
    
    /*
    |--------------------------------------
    |Get Nearby
    |--------------------------------------
    */

    public function getNearby($order_id,$type_staff)
    {
        // Obtenemos el pedido
        $order       = Order::find($order_id);
        //  Buscamos el id de la ciudad del comercio
        $city_id     = User::find($order->store_id)->city_id;
        // Obtenemos el arreglo de los repartidores
        $staff       = Delivery::where('store_id',0) // que sea del admin
                        ->where('status',0) // que este activo
                        ->where('status_admin',0) // que no este bloqueado
                        ->where('type_driver',$type_staff) // que sea del tipo de repartidor seleccionado
                        ->where('city_id',$city_id) // que este en la misma ciudad
                        ->get();
        
        $data  = [];
        $dist_min = 5;
        foreach ($staff as $key) {
            $lat = $key->lat;
            $lon = $key->lng;

            // Verificamos que todos tengan coordenadas validas
            if ($lat != null || $lat !='' && $lot != null || $lon !='') {    
                
                // hay que verificar que no esten con notificacion activa
                $notActive = Order_staff::where('d_boy',$key->id)->first();

                if (!$notActive) { // esta disponible

                    // Verificamos que este pedido no lo haya rechazado en caso de reasignacion
                    $rejected = Rate_staff::where('order_id',$order_id)->where('d_boy',$key->id)->where('status',2)->first();

                    if (!$rejected) {
                        // Comparamos las coordenadas entre el repartidor y la tienda para su distancia
                        $res  = User::where('id',$order->store_id)
                            ->select(DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                            * cos(radians(users.lat)) 
                            * cos(radians(users.lng) - radians(" . $lon . ")) 
                            + sin(radians(" .$lat. ")) 
                            * sin(radians(users.lat))) AS distance_store"),'users.*')
                            ->orderBy('id','DESC')->get();

                        foreach ($res as $staf) {

                            // Obtenemos la distancia de cada uno
                            $distancia_total = $staf->distance_store;

                            if ($distancia_total <= $dist_min) { // 5km distancia maxima
                                if (count($data) <= 2) {

                                    if ($key->amount_acum > 0) { // Filtramos por repartidor que tenga saldo positivo
                                        $data[] = [
                                            'type_Staff' => $type_staff,
                                            'city_id' => $city_id,
                                            'max_range_km' => $key->max_range_km,
                                            'distance_store' => $staf->distance_store,
                                            'distancia_total' => $distancia_total,
                                            'dboy' => $key->id,
                                            'external_id' => $key->external_id,
                                            'name' => $key->name
                                        ];
                                    }
                                }else {
                                    break;
                                }
                                
                            };
                        }
                    }
                }   
            } 
        }

        return [
            'dboys' => $data
        ];
    }

    public function setStaffOrder($order_id, $dboy_id)
    {
        // Checamos si el pedido ya fue tomado
        $order = Order::find($order_id);

        if ($order->d_boy != 0) {
            return [
                'status' => 'in_rute'
            ];
        }else {
            // Seteamos la tabla
            Order_staff::where('order_id',$order_id)->delete();

            // Guardamos el Nuevo elemento
            $order_Staff = new Order_staff;

            $order_Staff->external_id = $order->external_id;
            $order_Staff->order_id = $order_id;
            $order_Staff->d_boy    = $dboy_id;
            $order_Staff->status   = 0;
            $order_Staff->save();

            // Guardamos en su Score
            $req 	= new Rate_staff;
            $score = array(
                'order' => $order_id,
                'dboy'  => $dboy_id,
                'status'=> 0 // en espera
            );
            $req->addNew($score);

            // Notificamos al repartidor
            app('App\Http\Controllers\Controller')->sendPushD("Nuevo pedido recibido","Tienes una solicitud de pedido, ingresa para más detalles",$dboy_id);
            
            return [
                'status' => 'not_rute',
                'external_id'  => $order_Staff->external_id
            ];
        }
    }

    /**
     * 
     * Eliminamos al no tener respuesta de algun repartidor 
     * 
    */

    function delStaffOrder($order_id)
    {
        // Seteamos la tabla
        Order_staff::where('order_id',$order_id)->delete();

        $order = Order::find($order_id);

        $order->status = 1;
        $order->save();
        
        // Notificamos al negocio que no se encontraron repartidores
        $msg = "No hemos encontrado un repartidor disponible para tu solicitud, por favor vuelve a intentarlo";
        $title = "No encontramos repartidores!!";
        app('App\Http\Controllers\Controller')->sendPushS($title,$msg,$order->store_id);
        
        return [
            'status' => 'done'
        ];
    }

    function delStaffEvent($order_id)
    {
        // Seteamos la tabla
        Order_staff::where('event_id',$order_id)->delete();

        $order = Commaned::find($order_id);

        $order->status = 3;
        $order->save();
        
        // Notificamos al negocio que no se encontraron repartidores
        $msg = "No hemos encontrado un repartidor disponible para tu solicitud, por favor vuelve a intentarlo";
        $title = "No encontramos repartidores!!";
        app('App\Http\Controllers\Controller')->sendPushS($title,$msg,$order->store_id);
        
        return [
            'status' => 'done'
        ];
    }
}
