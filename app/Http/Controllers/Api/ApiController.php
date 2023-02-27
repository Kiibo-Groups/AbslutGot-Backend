<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OpenpayController;
use App\Http\Controllers\NodejsServer;
use App\Http\Controllers\WhatsAppCloud;

use Illuminate\Http\Request;
use Auth;
use App\City;
use App\OfferStore;
use App\Offer;
use App\User;
use App\Cart;
use App\CartCoupen;
use App\AppUser;
use App\Order;
use App\Order_staff;
use App\OrderAddon;
use App\OrderItem;
use App\Lang;
use App\Rate;
use App\Slider;
use App\Banner;
use App\Address;
use App\Admin;
use App\Page;
use App\Language;
use App\Text;
use App\Delivery;
use App\CategoryStore;
use App\Opening_times;
use App\CardsUser;
use App\Favorites;
use App\Tables;
use App\Commaned;
use App\Visits;
use App\Deposit;

use DB;
use Validator;
use Redirect;
use Excel;
use Stripe;
use Pusher;

class ApiController extends Controller {

	public function welcome()
	{
		$res = new Slider;

		return response()->json(['data' => $res->getAppData()]);
	}

	public function city()
	{
		$city = new City;
        $text = new Text;
        $lid =  isset($_GET['lid']) && $_GET['lid'] > 0 ? $_GET['lid'] : 0;

		return response()->json([
			'data' => $city->getAll(0),
			'text' => $text->getAppData($lid)
		]);
	}

	public function GetNearbyCity()
	{
		$city = new City;
        $text = new Text;
        $lid =  isset($_GET['lid']) && $_GET['lid'] > 0 ? $_GET['lid'] : 0;

		return response()->json([
			'data' => $city->GetNearbyCity(0),
			'text' => $text->getAppData($lid)
		]);
	}

	public function updateCity()
	{
		$res = AppUser::find($_GET['id']);
		$res->last_city = $_GET['city_id'];
		$res->save();

		return response()->json(['data' => 'done']);
	}

	public function lang()
	{
		$res = new Language;

		return response()->json(['data' => $res->getWithEng()]);
	}

	public function getDataInit()
	{
		$text    = new Text;
		$l 		 = Language::find($_GET['lid']);

		$data = [
			'text'		=> $text->getAppData($_GET['lid']),
			'app_type'	=> isset($l->id) ? $l->type : 0,
			'admin'		=> Admin::find(1),
		];

		return response()->json(['data' => $data]);
		
	}

	public function homepage($city_id)
	{
		$banner  = new Banner;
		$store   = new User;
		$text    = new Text;
		$offer   = new Offer;
		$cats    = new CategoryStore;
		$cat     = isset($_GET['cat']) ? $_GET['cat'] : 0;
		$l 		 = Language::find($_GET['lid']);

		$data = [
			'admin'		=> Admin::find(1),
			'banner'	=> $banner->getAppData($city_id,0),
			'middle'	=> $banner->getAppData($city_id,1),
			'bottom'	=> $banner->getAppData($city_id,2),
			'store'		=> $store->getAppData($city_id),
			'trending'	=> $store->InTrending($city_id), //$store->getAppData($city_id,true),
			'Categorys' => $cats->getSelectSubCat($cat),
			'offers'    => $offer->getAll(0),
			'Tot_stores'=> $store->getTotsStores($city_id)
		];

		return response()->json(['data' => $data]);
	}

	public function homepage_init($city_id)
	{
		$text    = new Text;
		$banner  = new Banner;
		$cats    = new CategoryStore;
	
		$data = [
			'admin'		=> Admin::find(1),
			'Categorys' => $cats->ViewOrderCats(),
			'banner'	=> $banner->getAppData($city_id,0),
		];

		return response()->json(['data' => $data]);
	}

	public function ViewAllCats()
	{
		try {
			$cats    = new CategoryStore;
			$cat     = isset($_GET['cat']) ? $_GET['cat'] : 0;
			$data = [
				'Categorys' => $cats->getSelectSubCat($cat),
			];

			return response()->json(['data' => $data]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getStoreOpen($city_id)
	{
		$store   = new User; 
		$data = [
			'store'		=> $store->getStoreOpen($city_id),
			'admin'		=> Admin::find(1),
		];

		return response()->json(['data' => $data]);		
	}

	public function getStore($id)
	{ 
		try {
			$store   = new User;
			return response()->json(['data' => $store->getStore($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function GetInfiniteScroll($city_id) {
		
		$store   = new User;
		
		$data = [
			'store'		=> $store->GetAllStores($city_id)
		];

		return response()->json(['data' => $data]);
	}

	public function getTypeDelivery($id)
	{
		$user = new User;
		return response()->json([$user->getDeliveryType($id)]);
	}

	public function search($query,$type,$city)
	{
		$user = new User;

		return response()->json(['data' => $user->getUser($query,$type,$city)]);
	}

	public function SearchCat($city_id)
	{
		try {
			$user = new User;
			return response()->json([
				'cat'	=> CategoryStore::find($_GET['cat'])->name,
				'data' 	=> $user->SearchCat($city_id)
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function SearchFilters($city_id)
	{
		try {
			$user = new User; 
			return response()->json([
				'data' 	=> $user->SearchFilters($city_id)
			]);
		} catch (\Exception $th) {
			return response()->json([
				'data' 	=> 'error',
				'error' => $th->getMessage()
			]);
		}
	}

	public function addToCart(Request $Request)
	{
		$res = new Cart;

		return response()->json(['data' => $res->addNew($Request->all())]);
	}

	public function updateCart($id,$type)
	{
		$res = new Cart;

		return response()->json(['data' => $res->updateCart($id,$type)]);
	}

	public function cartCount($cartNo)
	{
		try {
			$order = Order::where('cart_no',$cartNo)->whereIn('status',[0,1,1.5,3,4,5])->count();

			$cart = new Cart;
			$req  = new Order;

			return response()->json([
				'data'  => Cart::where('cart_no',$cartNo)->count(),
				'order' => $order,
				'data_order' => ($order > 0) ? Order::where('cart_no',$cartNo)->whereIn('status',[0,1,1.5,3,4,5])->first()->external_id : '',
				'list_orders' => ($order > 0) ? $req->getListOrder($cartNo) : [],
				'cart'	=> $cart->getItemQty($cartNo)
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getCart($cartNo)
	{
		try {
			$res = new Cart;
			return response()->json(['data' => $res->getCart($cartNo)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function deleteAll($cartNo)
	{
		$res = new Cart;

		return response()->json(['data' => $res->deleteAll($cartNo)]);
	}

	public function getOffer($cartNo)
	{
		$res = new Offer;

		return response()->json(['data' => $res->getOffer($cartNo)]);
	}

	public function applyCoupen($id,$cartNo)
	{
		$res = new CartCoupen;

		return response()->json($res->addNew($id,$cartNo));
	}

	public function signup(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function sendOTP(Request $Request)
	{
		$phone = $Request->phone;
		$hash  = $Request->hash;

		return response()->json(['otp' => app('App\Http\Controllers\Controller')->sendSms($phone,$hash)]);
	}

	public function SignPhone(Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->SignPhone($Request->all()));
	}

	public function chkUser(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->chkUser($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function login(Request $Request)
	{
		try {
			$res = new AppUser; 
			return response()->json($res->login($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
	}

	public function Newlogin(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json($res->Newlogin($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['msg' => 'error','error' => $th->getMessage()]);
		}
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

	public function loginFb(Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->loginFb($Request->all()));
	}

	public function getAddress($id)
	{
		$address = new Address;
		$cart 	 = new Cart;

		$data 	 = [
		'address'	 => $address->getAll($id),
		'Comercio'   => User::find($_GET['store']),
		'total'   	 => $cart->getCart($cartNo)['total'],
		'c_charges'  => $cart->getCart($cartNo)['c_charges']
		];

		return response()->json(['data' => $data]);
	}

	public function getAllAdress($id)
	{
		$address = new Address;
	
		return response()->json(['data' => $address->getAll($id)]);
	}

	public function addAddress(Request $Request)
	{
		$res = new Address;

		return response()->json($res->addNew($Request->all()));
	}

	public function removeAddress($id)
	{
		$res = new Address;
		return response()->json($res->Remove($id));
	}

	public function searchLocation(Request $Request)
	{
		$city = new City;
		return response()->json([
			'citys' => $city->getAll()
		]); 
	}

	public function order(Request $Request)
	{
		try {
			$res = new Order;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function userinfo($id)
	{
		try {
			$user = new AppUser;
			$deposit = new Deposit;
			return response()->json([
				'data' => AppUser::find($id),
				'cashback' => $user->getAllUser($id),
				'deposits' => $deposit->getDeposits($id)
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function signupOP(Request $Request)
	{
		try {
			$res = new AppUser;
			return response()->json(['data' => $res->signupOP($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}

	public function updateInfo($id,Request $Request)
	{
		$res = new AppUser;

		return response()->json($res->updateInfo($Request->all(),$id));
	}

	public function cancelOrder($id,$uid)
	{
		try {
			$res = new Order;
			return response()->json($res->cancelOrder($id,$uid));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function rate(Request $Request)
	{
		try {
			$rate = new Rate;
			return response()->json($rate->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}

	}

	public function pages()
	{
		$res = new Page;

		return response()->json(['data' => $res->getAppData()]);
	}

	public function myOrder($id)
	{
		$res = new Order;
		$req = new Commaned;

		return response()->json([
			'data' 		=> $res->history($id),
			'events' 	=> $req->history($id)
		]);
	}
 
	public function getChat($id)
	{
		
		// $msg = new Controller;
		// return response()->json(['data' => $msg->sendWeb("Nuevo pedido","Existe un nuevo pedido",$id)]);
		$title = "Nuevo pedido recibido!!";
		$message = " 🎉 Nuevo pedido recibido 🎉 #4 valor del pedido $250";

		app('App\Http\Controllers\Controller')->sendWeb($title,$message,'4');
	}

	public function getStatus($id)
	{
		try {
			$order = Order::find($id);
			$dboy  = Delivery::find($order->d_boy);
			$store = User::find($order->store_id);

			return response()->json(['data' => $order,'dboy' => $dboy, 'store' => $store]);
		} catch (\Throwable $th) {
			return response()->json(['data' => [],'dboy' => [], 'store' => []]);
		}
	}

	public function getPolylines()
	{
		$url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$_GET['latOr'].",".$_GET['lngOr']."&destination=".$_GET['latDest'].",".$_GET['lngDest']."&mode=driving&key=".Admin::find(1)->ApiKey_google;
		$max      = 0;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);


		$request = json_decode($output, true);

		return response()->json($request);
	}

	public function sendChat(Request $Request)
	{
		$chat = new Chat;
		return response()->json($chat->addNew($Request->all()));
	}

	public function deleteOrders (Request $Request)
	{
		$items  = $Request->all()['SendChk'];

		for ($i=0; $i < count($items); $i++) { 
			Order::find($items[$i])->delete();
			Order_staff::where('order_id',$items[$i])->delete();
			OrderAddon::where('order_id',$items[$i])->delete();
			OrderItem::where('order_id',$items[$i])->delete();
		}	

		return response()->json(['data' => 'done']);
	}

	/**
	 * Metodos OpenPay
	 */

	public function getClient(Request $Request)
	{
		try {
			$openPay = new OpenpayController;
			return response()->json(['data' => $openPay->getClient($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function SetCardClient(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			$req     = $openpay->SetCardClient($Request->all());
			if ($req['status'] == true) {
				$user = AppUser::find($Request->get('user_id'));
				$card = new CardsUser;
				$data 	 = [
					'user_id'	 	=> $user->id,
					'token_card'   	=> $req['data']['id']
				];

				$card->addNew($data,'add');
			}

			return response()->json(['data' => $req]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error",'error' => $th]);
		}
	}

	public function GetCards(Request $Request)
	{
		try {
			$openpay = new OpenpayController; 
			return response()->json(['data' => $openpay->getCardsClient($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function DeleteCard(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			
			return response()->json(['data' => $openpay->DeleteCard($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function getCard(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			
			return response()->json(['data' => $openpay->getCard($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	}

	public function chargeClient(Request $Request)
	{
		try {
			$openpay = new OpenpayController;
			
			return response()->json(['data' => $openpay->chargeClient($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'msg' => $th->getMessage()]);
		}
	}

	public function addBalance(Request $Request)
	{
		try {
			$data = $Request->all();
			Deposit::create($data);	

			$user = AppUser::find($data['user_id']);

			$saldo = $user->saldo;
			$user->saldo = $saldo + $data['amount'];
			$user->save();

			return response()->json(['data' => 'done']);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	} 

	public function setTableCustomer($id)
	{
		try {
			$res 			= Tables::where("mesa",$id)->first();
			if ($res) { 
				if ($res->status == 1) { // La mesa esta tomada
					return response()->json(['data' => 'table_inuse']);
				}else {
					$res->status = 1;
					$res->save();
					return response()->json(['data' => 'done']);
				}
			}else {
				return response()->json(['data' => 'not_found_table']);
			}
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'msg' => $th->getMessage()]);
		}
	}


	/**
	 *
	 * Stripe & Stripe Connect
	 *  
	 */ 

	public function StripeConnectWebhook()
	{ 
		// This is your Stripe CLI webhook secret for testing your endpoint locally.
		$endpoint_secret = 'whsec_Olp0tAuSEkFkkOzDgizm22WgNfWXtv13';

		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$event = null;

		try {
		$event = \Stripe\Webhook::constructEvent(
			$payload, $sig_header, $endpoint_secret
		);
		} catch(\UnexpectedValueException $e) {
		// Invalid payload
		http_response_code(400);
		exit();
		} catch(\Stripe\Exception\SignatureVerificationException $e) {
		// Invalid signature
		http_response_code(400);
		exit();
		}

		$account_user;
		// Handle the event
		switch ($event->type) {
			case 'account.external_account.created':
				$application = $event->data->object;
				$account_user = $event->account;
				$user		  = User::where('stripe_account',$account_user)->first();
				if ($user) {
					$user->external_id_stripe = $application->id;
					$user->save();
				}
				echo 'Usuario '.$user->name.' ha sido acreado... ID'.$application->id;

			case 'account.application.authorized':
				$application = $event->data->object;
				$account_user = $event->account;
				$user		  = User::where('stripe_account',$account_user)->first();
				if ($user) {
					$user->external_id_stripe = $application->id;
					$user->save();
				}
				echo 'Usuario '.$user->name.' ha sido acreado... ID'.$application->id; 
			default:
		}
		http_response_code(200);
	}

	public function stripe()
	{

		$user_stripe_id = $_GET['user_stripe_id'];
		$amount			= $_GET['amount'];
		$token 			= (isset($_GET['token'])) ? $_GET['token'] : ''; 
		$comm_stripe	= Admin::find(1)->comm_stripe;
		$comm_stripe_st = Admin::find(1)->comm_stripe_st;

		$application_fee_amount = 0;
		if($comm_stripe_st) {
			$application_fee_amount = ($amount * $comm_stripe) / 100;
		}

		try {
			Stripe\Stripe::setApiKey(Admin::find(1)->stripe_api_id);

			$payment_intent = Stripe\Charge::create ([
				"amount" => $amount * 100,
				"currency" => "MXN",
				"source" => $token,
				'application_fee_amount' => round($application_fee_amount) * 100, 
			], ['stripe_account' => $user_stripe_id]);

			
			if($payment_intent['status'] === "succeeded")
			{
				return response()->json(['data' => "done",'id' => $payment_intent['source']['id']]);
			}
			else
			{
				return response()->json(['data' => "error",'log' => $payment_intent]);
			}
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}

	/**
	 * 
	 *  Favorites Funcions
	 * 
	 */

	 public function SetFavorite(Request $Request)
	 {
		try {
			$req = new Favorites;
			
			return response()->json(['data' => $req->addNew($Request->all())]);
		} catch (\Throwable $th) {
			return response()->json(['data' => "error"]);
		}
	 }

	 public function GetFavorites($id)
	 {
		try {
			$req = new Favorites;
			
			return response()->json(['data' => $req->GetFavorites($id)]);	
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	 }

	 public function TrashFavorite($id, $user)
	 {
		try {
			$req = new Favorites;
			return response()->json(['data' => $req->TrashFavorite($id, $user)]);	
		} catch (\Throwable $th) {
			return response()->json(['data' => "error",'error' => $th]);
		}
	 }


	/**
	  * 
	  * Solcitud de repartidores cercanos
	  *
	 */

	public function getNearbyStaffs($order,$type_staff)
	{
		// Obtenemos repartidores Mas cercanos
		$delivery = new Delivery;
		return response()->json(['data' => $delivery->getNearby($order, $type_staff)]);
	}

	public function setStaffOrder($order, $dboy)
	{
		// Chequeo de pedido y registro de repartidores
		$delivery = new Delivery;
		return response()->json(['data' => $delivery->setStaffOrder($order,$dboy)]);	
	}

	public function delStaffOrder($order)
	{
		// Chequeo de pedido y registro de repartidores
		$delivery = new Delivery;
		return response()->json(['data' => $delivery->delStaffEvent($order)]);	
	}

	public function updateStaffDelivery($staff, $external_id)
	{
		$staff = Delivery::find($staff);

		$staff->external_id = $external_id;
		$staff->save();

		return response()->json(['data' => 'done']);
	}

	/**
	  * 
	  * Seccion de mandaditos
	  *
	*/

	public function OrderComm(Request $Request)
	{
		try {
			$res = new Commaned;
			return response()->json($res->addNew($Request->all()));
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function ViewCostShipCommanded(Request $Request)
	{
		try {
			
			$req = new Commaned;

			return response()->json(['data' => $req->Costs_shipKM($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'fail', 'error' => $th->getMessage()]);
		}
	}

	public function chkEvents_comm($id)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->chkEvents_comm($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => "error",'error' => $th->getMessage()]);
		}
	}

	public function chkEvents_staffs(Request $Request)
	{
		// Reseteamos
		$event = Commaned::find($Request->get("id_order"));
		$event->status = 0;
		$event->save();

		$req = new NodejsServer;
		
		return response()->json(['data' => $req->NewOrderComm($Request->all()),'req' => $Request->all()]);
	}

	public function getNearbyEvents($id)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->getNearby($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => $id, 'error' => $th->getMessage()]);
		}
	}

	public function setStaffEvent($event_id,$dboy)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->setStaffEvent($event_id,$dboy)]);	
		} catch (\Exception $th) {
			return response()->json(['data' => $id, 'error' => $th->getMessage()]);
		}
	}

	public function delStaffEvent($event_id)
	{
		$req = new Commaned;
		return response()->json(['data' => $req->delStaffEvent($event_id)]);
	}

	public function cancelComm_event($event_id)
	{
		$req = new Commaned;
		return response()->json(['data' => $req->cancelComm_event($event_id)]);
	}

	public function rateComm_event(Request $Request)
	{
		try {
			$req = new Commaned;
			return response()->json(['data' => $req->rateComm_event($Request->all())]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function SetNewVisitStore($store_id,$user_id)
	{
		try {
			$visit = new Visits;
			return response()->json(['data' => $visit->addNew($store_id,$user_id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}


	/**
	 * 
	 * Categorias
	 * 
	 */
	public function getCategory($id)
	{
		try {
			$req = new CategoryStore; 
			return response()->json(['data' => $req->getSelectCat($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}

	public function getSelectSubCat($id)
	{
		try {
			$req = new CategoryStore; 
			return response()->json(['data' => $req->getSelectSubCat($id)]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}


	/**
	 * Tracking Web
	 */
	public function chkTrackingOrder($id)
	{
		
		try{
			$orders = Order::where(function($query) use($id){
				$query->where('orders.external_id',$id);
				$query->whereIn('orders.status',[0,1,1.5,3,4,5,6]);
			})->join('users','orders.store_id','=','users.id')
			->leftjoin('delivery_boys','orders.d_boy','=','delivery_boys.id')
			->select('users.name as store','orders.*','delivery_boys.name as dboy')
			->orderBy('id','DESC')
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

				$countRate = Rate::where('order_id',$order->id)->where('user_id',$id)->first();
				$tot_com   = $order->total - $order->d_charges;

				$data = [
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
					'pay'       => $order->payment_method
				];
			}
 
			return response()->json(['data' => $data]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error','error' => $th->getMessage()]);
		}
	}
}
