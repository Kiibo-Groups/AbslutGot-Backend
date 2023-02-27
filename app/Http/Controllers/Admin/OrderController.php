<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\City;
use App\Delivery;
use App\Admin;
use App\Item;
use App\Order;
use App\Order_staff;
use App\OrderAddon;
use App\Tables;
use App\AppUser;
use App\OrderItem;
use DB;
use Validator;
use Redirect;
use IMS;
class OrderController extends Controller {

	public $folder  = "admin/order.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Order;
		$status = 0;
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
			if($_GET['status'] == 0)
			{
				$title = "Nuevos pedidos";
			}
			elseif($_GET['status'] == 1)
			{
				$title = "Ordenes en ejecución";
			}
			elseif($_GET['status'] == 2)
			{
				$title = "Órdenes canceladas";
			}
			elseif($_GET['status'] == 5)
			{
				$title = "Órdenes completadas";
			}
			else {
				$title = 'Pedidos';
			}

		}else {
			$title = 'Pedidos';
		}

		$admin = new Admin;

		if ($admin->hasperm('Gestion de pedidos')) {
			return View($this->folder.'index',[
				'data' 		=> $res->getAll(),
				'link' 		=> env('admin').'/order/',
				'title' 	=> $title,
				'status'    => $status,
				'item'		=> new OrderItem,
				'boys'		=> Delivery::where('status',0)->where('store_id',0)->get(),
				'arraydboy' => [],
				'form_url'	=> env('admin').'/order/dispatched',
				'currency'	=> Admin::find(1)->currency
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pedidos');
		}	
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

		}elseif ($_GET['status'] == 2) {
			Order_staff::where('order_id',$_GET['id'])->delete();
			$res->status 		= $_GET['status'];
		}elseif ($_GET['status'] == 7) {
			$res->status 		= 3;
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
			'change_from'   => 'admin'
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
		
		$res->sendSms($_GET['id']);

		if($_GET['status'] == 1){
			return Redirect::to(env('admin').'/order?status=1')->with('message', 'Pedido aceptado, se ha enviado notificación al cliente.');
		}else {
			return Redirect::back()->with('message','El status del pedido #'.$_GET['id']." ha sido cambiado con éxito.");
		}
		 
	}

	public function dispatched(Request $Request)
	{
		$res 				= Order::find($Request->get('id'));
		$res->status 		= 1.5;
		$res->status_by 	= 1;
		$res->d_boy 		= 0;
		$res->status_time 	= date('d-M-Y').' | '.date('h:i:A');
		$res->save();
		
		// Marcamos como libre el antiguo repartidor
		if ($res->d_boy) {
			$staff 						= $res->d_boy;
			$staff_old          		= Delivery::find($staff);
			$staff_old->status_send  	= 0;
			$staff_old->save();
		}
		
		// Cambiamos el status en FB 
		$fb_server = new NodejsServer;
		$dat_s = array(
			'external_id' 	=> $res->external_id,
			'status' 		=> $res->status,
			'change_from'   => 'admin'
		);
		$fb_server->orderStatus($dat_s);
		
		// Limpiamos la tabla si esque habia Elementos
		Order_staff::where('order_id',$Request->get('id'))->delete();
		// Registramos el repa solicitado
		$staff_Ext = new Order_staff;
		$staff_Ext->external_id = $res->external_id;
		$staff_Ext->order_id    = $Request->get('id');
		$staff_Ext->d_boy       = $Request->get('d_boy');
		$staff_Ext->status      = 0;

		$staff_Ext->save();
		// Notificamos al repartidor
		app('App\Http\Controllers\Controller')->sendPushD("Nuevo pedido recibido","Tienes una solicitud de pedido, ingresa para más detalles",$Request->get('d_boy'));

		// Notificamos al usuario
		$res->sendSms($Request->get('id'));
		
		return Redirect::back()->with('message','Solicitud enviada a ambos destinatarios.'); 
	}

	public function printBill($id)
	{

		$admin = new Admin;

		if ($admin->hasperm('Gestion de pedidos')) {
		$order = new Order;
		$item  = new OrderItem;

		return View('admin.order.print',[

		'order' 	=> $order->signleOrder($id),
		'items'		=> $item->getItem($id),
		'currency'	=> Admin::find(1)->currency,
		'it'		=> $item

		]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pedidos');
		}
	}

	public function edit($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('Gestion de pedidos')) {
		$order = Order::find($id);
		$item  = new OrderItem;

		return View($this->folder.'edit',[

		'data' 		=> $order,
		'item'		=> Item::where('store_id',$order->store_id)->get(),
		'detail'	=> $item->detail($id),
		'form_url'	=> env('admin').'/order/edit/'.$id,
		'users'		=> User::get()


		]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pedidos');
		}
	}

	public function orderItem()
	{
		$admin = new Admin;

		if ($admin->hasperm('Gestion de pedidos')) {
		return View($this->folder.'item',['item' => Item::where('store_id',$_GET['store_id'])->get(),'data' => new Order]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pedidos');
		}
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

		return Redirect(env('admin').'/order?status=1')->with('message','Order Edit Successfully');
	}

	public function add()
	{
		$admin = new Admin;

		if ($admin->hasperm('Gestion de pedidos')) {
		return View($this->folder.'add',[

		'data' 		=> new Order,
		'item'		=> Item::get(),
		'form_url'	=> env('admin').'/order/add',
		'users'		=> User::get()

		]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pedidos');
		}
	}

	public function _add(Request $Request)
	{
		$order = new Order;

		$order->editOrder($Request->all(),0);

		return Redirect(env('admin').'/order?status=1')->with('message','Order Added Successfully');
	}

	public function delete($id)
	{
		Order::find($id)->delete();
		Order_staff::where('order_id',$id)->delete();
		OrderAddon::where('order_id',$id)->delete();
		OrderItem::where('order_id',$id)->delete();

		return redirect::back()->with('message','Elemento Eliminado..');
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

	public function getCity($id,$type)
	{
		echo $type;
	}
}