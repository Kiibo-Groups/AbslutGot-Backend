<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\City;
use App\UserImage;
use App\Order;
use App\Admin;
use App\CategoryStore;
use App\Opening_times;
use DB;
use Validator;
use Redirect;
use Stripe;


class AdminController extends Controller {

	public $folder = "user.";

	/*
	|------------------------------------------------------------------
	|Index page for login
	|------------------------------------------------------------------
	*/
	public function index()
	{
		return View($this->folder.'index',[
			'form_url' => Asset(env('user').'/login'),
			'currency' => Admin::find(1)->currency
		]);
	}

	/*
	|------------------------------------------------------------------
	|Login attempt,check username & password
	|------------------------------------------------------------------
	*/
	public function login(Request $request)
	{
		$username = $request->input('username');
		$password = $request->input('password');
		
		if (auth()->attempt(['email' => $username, 'password' => $password,'status' => 0]))
		{
			return Redirect::to(env('user').'/home')->with('message', 'Bienvenido(a)! de nuevo');
		}
		else
		{
			return Redirect::to(env('user').'/login')->with('error', 'El usuario y/o la contraseña no coinciden')->withInput();
		}
	}

	/*
	|------------------------------------------------------------------
	|Homepage, Dashboard
	|------------------------------------------------------------------
	*/
	public function home()
	{			
		$user 	= new User;
		$order = new Order;
		$admin = new Admin;
			
		return View($this->folder.'dashboard.home',[
			'overview'	=> $user->overview(),
			'admin'		=> $admin,
			'currency'  => Admin::find(1)->currency
		]);	
	}

	/*
	|------------------------------------------------------------------
	|Logout
	|------------------------------------------------------------------
	*/
	public function logout()
	{
		auth()->logout();
		
		return Redirect::to(env('user').'/login')->with('message', 'Cerro sesión con éxito!');
	}

	/*
	|------------------------------------------------------------------
	|Account setting's page
	|------------------------------------------------------------------
	*/
	public function setting()
	{
		$city = new City;
		$cats = new CategoryStore;
		$times = new Opening_times;
		return View($this->folder.'dashboard.setting',[

			'data' 		=> User::find(Auth::user()->id),
			'form_url'	=> Asset(env('user').'/setting'),
			'type_ship'  => Admin::find(1)->c_type,
			'costs_ship' => Admin::find(1)->c_value,
			'ApiKey'     => Admin::find(1)->ApiKey_google,
			'min_costs_ship' => Admin::find(1)->min_distance,
			'min_charges_value' => Admin::find(1)->min_value,
			'citys'		=> $city->getAll(0),
			'images' 	=> UserImage::where('user_id',Auth::user()->id)->get(),
			'types'		=> explode(",",Admin::find(1)->store_type),
			'types'		=> $cats->getAll(),
			'Update'    => true,
			'times'     => $times->getAll(Auth::user()->id),
			'opening_time' => $times,
			'linkStripe' => env('user').'/'
		]);
	}

	public function stripeConnect($id)
	{

		$user = User::find($id);
		$admin = Admin::find(1); 
		$stripe_api_id 	= $admin->stripe_api_id;

		$stripe = new \Stripe\StripeClient($stripe_api_id);
 
		$url = $stripe->accountLinks->create([
			'account' => $user->stripe_account, 
			'refresh_url' => 'https://bincar.kiibo.mx/setting',
			'return_url' => 'https://bincar.kiibo.mx/setting',
			'type' => 'account_onboarding',
		]); 

		// return response()->json([
		// 	'url' => $url
		// ]);
		return Redirect::to($url->url);
	}
	
	/*
	|------------------------------------------------------------------
	|update account setting's
	|------------------------------------------------------------------
	*/
	public function update(Request $Request)
	{		
		$data = new User;
		$id   = Auth::user()->id;
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id);

		return Redirect::back()->with('message','Información de la cuenta actualizada con éxito.');
	}

	public function close()
	{
		$res 		= User::find(Auth::user()->id);
		$res->open  = $res->open == 0 ? 1 : 0;
		$res->save();

		return Redirect::back()->with('message','Cambios de estado con éxito.');

	}
}