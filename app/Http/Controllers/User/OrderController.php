<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\City;
use App\Order;
use App\OrderItem;
use App\Delivery;
use App\Admin;
use App\Item;
use App\Tables;
use App\AppUser;
use App\Order_staff;
use DB;
use Validator;
use Redirect;
use IMS;
class OrderController extends Controller {

	public $folder  = "user.order.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Order;
		
		if($_GET['status'] == 0)
		{
			$title = "Nuevos pedidos";
		}
		elseif($_GET['status'] == 1)
		{
			$title = "Ordenes en ejecución";
		}
		elseif($_GET['status'] == 1.5)
		{
			$title = "Ordenes en espera de repartidor.";
		}
		elseif($_GET['status'] == 2)
		{
			$title = "Órdenes canceladas";
		}
		elseif($_GET['status'] == 3)
		{
			$title = "Pedidos despachados";
		}
		elseif($_GET['status'] == 4)
		{
			$title = "Pedidos despachados";
		}
		elseif($_GET['status'] == 5)
		{
			$title = "Órdenes completadas";
		}else {
			$title = '';
		}

		return View($this->folder.'index',[
			'data' 		=> $res->getAll(null,Auth::user()->id),
			'link' 		=> 'order/',
			'title' 	=> $title,
			'item'		=> new OrderItem,
			'boys'		=> Delivery::where('status',0)->where('store_id',Auth::user()->id)->get(),
			'boysApp'   => Delivery::where('status',0)->where('city_id',Auth::user()->city_id)->where('store_id',0)->get(),
			'p_staff'   => Auth::user()->p_staff,
			'form_url'	=> 'order/dispatched',
			'currency'	=> Admin::find(1)->currency
		]);
	}	

	public function orderStatus()
	{
		$res 				= Order::find($_GET['id']);
		
		if($_GET['status'] == 5){
			$saldos_m = new User;
			$saldos_m->setNewMov($_GET['id'],$res->store_id,$res->total,$res->d_charges);
			$res->status 		= 6;

			if ($res->InnStore == true) {
				$tabl = Tables::where('mesa',$res->mesa)->first();
				$tabl->status = 0;
				$tabl->save();
			} 
 
		}elseif ($_GET['status'] == 7) {
			$res->type = $_GET['status']; 
		}elseif ($_GET['status'] == 2) {
			Order_staff::where('order_id',$_GET['id'])->delete();
			$res->status 		= $_GET['status'];
		}elseif ($_GET['status'] == 1){ // Pedido aceptado
			$res->status 		= $_GET['status'];

			/** Notificamos al panel de control */ 
			$title = "Pedido Aceptado!!";
			$message = "🎉 Nuevo pedido recibido #".$res->id."🎉";
			app('App\Http\Controllers\Controller')->sendKitchen($title,$message,strval($res->store_id));
			/** Notificamos al panel de control */
		}else {
			$res->status 		= $_GET['status'];
		}
		
		$res->status_by 	= 1;
		$res->status_time 	= date('d-M-Y').' | '.date('h:i:A');
		$res->save();

		// Cambiamos el status en FB 
		$fb_server = new NodejsServer;
		$dat_s = array(
			'external_id' 	=> $res->external_id,
			'status' 		=> $res->status,
			'change_from'   => 'store'
		);
		
		$fb_server->orderStatus($dat_s); 

		if (isset($_GET['staff_ext'])) {
			$res->d_boy = 0;
			$res->save();

			// 0 = Auto, 1 = Moto, 2 = Bici
			$type_staff = isset($_GET['type_staff']) ? $_GET['type_staff'] : 1;

			// Enviamos al servidor
			$dat_s = array(
				'order_id'		=> $_GET['id'],
				'type_staff'    => $type_staff,
			);

			$fb_server->setStaffDelivery($dat_s); 
			
		}
  
		return Redirect::back()->with('message','Estado del pedido cambiado correctamente'); 
	}

	public function dispatched(Request $Request)
	{ 
		$res 				= Order::find($Request->get('id')); 
		$chk_ext 			= Order_staff::where('order_id',$res->id)->get();

		if ($chk_ext->count() > 0) Order_staff::where('order_id',$res->id)->delete();
		
		// Cambiamos status en el pedido
		$res->status 		= 3;
		$res->status_by 	= 1;
		$res->d_boy 		= $Request->get('d_boy');
		$res->status_time 	= date('d-M-Y').' | '.date('h:i:A');
		$res->save();

		// Cambiamos el status en FB secundario
		$fb_server = new NodejsServer;
		$dat_s = array(
			'external_id' 	=> $res->external_id,
			'status' 		=> $res->status,
			'change_from'   => 'store'
		);
		
		$fb_server->orderStatus($dat_s); 

		// Ponemos al repartidor en modo activo
		$staff       = Delivery::find($Request->get('d_boy'));
		$staff->status_send = 1;
		$staff->save();

		// Guardamos el repartidor que tomo el pedido
		$order_Ext = new Order_staff;
		$order_Ext->d_boy 		= $Request->get('d_boy');
		$order_Ext->order_id 	= $Request->get('id');
		$order_Ext->status 		= 3;
		$order_Ext->save();

		// Notificamos al usuario
		$res->sendSms($Request->get('id'));

		return Redirect::back()->with('message','Estado del pedido cambiado correctamente'); 
	}

	public function printBill($id)
	{
		$order = new Order;
		$item  = new OrderItem;

		return View('user.order.print',[

		'order' 	=> $order->signleOrder($id),
		'items'		=> $item->getItem($id),
		'currency'	=> Admin::find(1)->currency,
		'it'		=> $item

		]);
	}

	public function edit($id)
	{
		$order = Order::find($id);
		$item  = new OrderItem;

		return View($this->folder.'edit',[

		'data' 		=> $order,
		'item'		=> Item::where('store_id',Auth::user()->id)->get(),
		'detail'	=> $item->detail($id),
		'form_url'	=> 'order/edit/'.$id,
		'users'		=> User::get()
		

		]);
	}

	public function orderItem()
	{
		return View($this->folder.'item',['item' => Item::where('store_id',Auth::user()->id)->get(),'data' => new Order]);
	}

	public function getUnit($id)
	{
		$order = new Order;

		$html = "<select name='unit[]'' class='form-control' required='required'>";

		foreach($order->getUnit($id) as $u)
		{
			$html .= "<option value='".$u['id']."'>".$u['name']."</option>";
		}

		$html .= "</select>";

		return $html;
	}

	public function _edit(Request $Request,$id)
	{
		$order = new Order;

		$order->editOrder($Request->all(),$id);

		return Redirect('order?status=1')->with('message','Order Edit Successfully');
	}

	public function add()
	{
		return View($this->folder.'add',[

		'data' 		=> new Order,
		'item'		=> Item::get(),
		'form_url'	=> 'order/add',
		'users'		=> User::get()

		]);
	}

	public function _add(Request $Request)
	{
		$order = new Order;

		$order->editOrder($Request->all(),0);

		return Redirect('order?status=1')->with('message','Order Added Successfully');
	}

	public function getUser($phone)
	{
		$user = Order::where('phone',$phone)->first();

		if(isset($user->id))
		{
			$array = ['phone' => $user->phone,'name' => $user->name,'address' => $user->address,'lat' => $user->lat, 'lng' => $user->lng];
		}
		else
		{
			$array = [];
		}

		return $array;
	}

	/**
	 * 
	 * Pedidos en cocina
	 * 
	 */
	public function kitchen_orders()
	{
		$res = new Order;
		 
		// return response()->json(['data' => $res->getKitchenOrder(null,Auth::user()->id)]);
		$new_order = DB::table('orders')->where('store_id',Auth::user()->id)->whereIn('status',[1])->count();
		$order_procs = DB::table('orders')->where('store_id',Auth::user()->id)->whereIn('status',[1.5])->count();
		$order_ready = DB::table('orders')->where('store_id',Auth::user()->id)->whereIn('status',[3])->count(); 

		return View($this->folder.'kitchen',[
			'data' 		=> $res->getKitchenOrder(null,Auth::user()->id),
			'new_order' => $new_order,
			'order_procs' => $order_procs,
			'order_ready' => $order_ready,
			'link' 		=> 'order/', 
			'item'		=> new OrderItem,
			'p_staff'   => Auth::user()->p_staff,
			'currency'	=> Admin::find(1)->currency
		]);
	}
}