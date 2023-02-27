<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;

use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\Order;
use App\Language;
use App\Text;
use App\User;
use App\City;
use App\Admin;
use App\Item;
use App\Order_staff;
use App\AppUser;
use DB;
use Validator;
use Redirect;
use Excel;
use Stripe;
class StoreController extends Controller {

	public function homepage()
	{
		$res 	 = new Order;
		$text    = new Text;
		$l 		 = Language::find($_GET['lid']);

		return response()->json([
			'data' 		=> $res->storeOrder(),
			'complete' 	=> $res->storeOrder(6),
			'text'		=> $text->getAppData($_GET['lid']),
			'admin'		=> Admin::find(1),
			'app_type'	=> isset($l->id) ? $l->type : 0,
			'store'		=> User::find($_GET['id']),
			'overview'	=> $res->overView(),
			'dboy'		=> Delivery::where('status',0)->get()
		]);
	}

	public function orderProcess()
	{
		try {
			$res 		 = Order::find($_GET['id']);
			$data_deli   = '';
			$res->status 		= $_GET['status'];
			$res->save();

			if ($_GET['status'] == 5) {
				// Agregamos al Monedero electronico
				$amount_mon = $res->monedero;
				$user = new AppUser;
				$user->addMoney($amount_mon,$res->user_id,$res->use_mon);
			}
			
			// Cambiamos el status en FB 
			$fb_server = new NodejsServer;
			$dat_s = array(
				'external_id' 	=> $res->external_id,
				'status' 		=> $res->status,
				'change_from'   => 'store_app'
			);
			$fb_server->orderStatus($dat_s); 

			if (isset($_GET['dboy_Ext'])) {
				$res->d_boy = 0;
				$res->save();
				// 0 = Auto, 1 = Moto, 2 = Bici
				$type_staff = isset($_GET['type_staff']) ? $_GET['type_staff'] : 1;

				// Enviamos al servidor
				$dat_s = array(
					'order_id'		=> $_GET['id'],
					'type_staff'    => $type_staff,
				);

				$data_deli = $fb_server->setStaffDelivery($dat_s); 
				
			}

			//$res->sendSms($_GET['id']);
			return response()->json(['data' => $_GET['id'] , 'data_deli' => $data_deli]);
		} catch (\Throwable $th) {
			return response()->json(['data' => 'fail' , 'data_deli' => []]);
		}
	}

	public function city()
	{
		$city = new City;
        $text = new Text;
        
		return response()->json(['data' => $city->getAll(0)]);
	}

	public function updateCity()
	{
		$admin = Admin::find($_GET['user_id']);

		$admin->city_notify = $_GET['city_id'];
		$admin->save();

		return response()->json(['data' => 'done']);
	}

	public function getStaffNearby($id)
	{
		$staff = new Delivery;

		return response()->json(['dboy' => $staff->getNearby($id)]);
	}

	public function overview()
	{
		$res 	 = new User;

		return response()->json([
			'data' 		=> $res->overview_app()
		]);
	}

	public function login(Request $Request)
	{
		$res = new User;
		
		return response()->json($res->login($Request->all()));
	}

	public function forgot(Request $Request)
	{
		$res = new AppUser;
		
		return response()->json($res->forgot($Request->all()));
	}

	public function verify(Request $Request)
	{
		$res = new AppUser;
		
		return response()->json($res->verify($Request->all()));
	}

	public function updatePassword(Request $Request)
	{
		$res = new AppUser;
		
		return response()->json($res->updatePassword($Request->all()));
	}

	public function userInfo($id)
	{
		return response()->json(['data' => User::find($id)]);
	}

	public function storeOpen($type)
	{
		try {
			$res 		= User::find($_GET['user_id']);
			$res->open 	= $type;
			$res->save();

			return response()->json(['data' => true]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}

	public function updateInfo(Request $Request)
	{
		$res 				= User::find($Request->get('id'));
		
		if($Request->get('password'))
		{
			$res->password      = bcrypt($Request->get('password'));
        	$res->shw_password  = $Request->get('password');
		}

		$res->min_cart_value 		 = $Request->get('min_cart_value');
		$res->delivery_charges_value = $Request->get('delivery_charges_value');
		$res->save();

		return response()->json(['data' => true]);
	}

	public function updateLocation(Request $Request)
	{
		if($Request->get('user_id') > 0)
		{
			$add 			= Delivery::find($Request->get('user_id'));
			$add->lat 		= $Request->get('lat');
			$add->lng 		= $Request->get('lng');
			$add->save();
		}

		return response()->json(['data' => true]);
	}

	public function getItem()
	{
		$res = new User;

		return response()->json(['data' => $res->menuItem($_GET['id'],$_GET['type'],$_GET['value'])]);
	}

	public function changeStatus()
	{
		$res 		 = Item::find($_GET['id']);
		$res->status = $_GET['status'];
		$res->save();

		return response()->json(['data' => true]);
	}
}