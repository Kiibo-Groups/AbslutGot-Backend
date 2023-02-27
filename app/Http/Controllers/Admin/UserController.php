<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\City;
use App\UserImage;
use App\Admin;
use App\CategoryStore;
use App\Opening_times;
use App\Addon;
use App\Item;
use App\ItemAddon;
use App\Rate;
use DB;
use Validator;
use Redirect;
use IMS;
use Stripe;

class UserController extends Controller {

	public $folder  = "admin/user.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$admin = new Admin;
        $city = Auth::guard('admin')->user()->city_id;

		if($admin->hasperm('Adminisrtar Restaurantes')) {
           
                $res = new User;
		        return View(
					$this->folder.'index',
					[
						'data' => $res->getAll($city),
						'link' => env('admin').'/user/',
						'currency' => Admin::find(1)->currency,
						'cats'  => new CategoryStore
					]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Adminisrtar Restaurantes');
		}
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{		
		$admin = new Admin;						
		if ($admin->hasperm('Adminisrtar Restaurantes')) {
			$city = new City;
			$cats = new CategoryStore;
			$times = new Opening_times;
			return View($this->folder.'add',[

				'data' 		 => new User,
				'type_ship'  => Admin::find(1)->c_type,
				'costs_ship' => Admin::find(1)->c_value,
				'ApiKey'     => Admin::find(1)->ApiKey_google,
				'form_url'  => env('admin').'/user',
				'citys'     => $city->getAll(0),
				'admin'		=> true,
				'Update'    => false,
				'times'     => $times->getAll(0),
				'opening_time' => $times,
				'cat_p'		=> $cats->getCatP(),
				'linkStripe' => env('admin').'/user/'
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Adminisrtar Restaurantes');
		}
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new User;

		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		return redirect(env('admin').'/user')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$admin = new Admin;

		if ($admin->hasperm('Adminisrtar Restaurantes')) {
			$city  = new City;
			$cats  = new CategoryStore;
			$times = new Opening_times;
			return View($this->folder.'edit',[
				'data' 		=> User::find($id),
				'form_url'  => env('admin').'/user/'.$id,
				'type_ship'  => Admin::find(1)->c_type,
				'costs_ship' => Admin::find(1)->c_value,
				'ApiKey'     => Admin::find(1)->ApiKey_google,
				'citys'     => $city->getAll(0),
				'images' 	=> UserImage::where('user_id',$id)->get(),
				'types'		=> explode(",",Admin::find(1)->store_type),
				'admin'		=> true,
				'cat_p'		=> $cats->getCatP(),
				'Update'    => true,
				'times'     => $times->getAll($id),
				'opening_time' => $times,
				'linkStripe' => env('admin').'/user/'
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Adminisrtar Restaurantes');
		}
	}

	
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new User;
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id);
		
		return redirect(env('admin').'/user')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		User::where('id',$id)->delete();
		Opening_times::where('store_id', $id)->delete();

		// Eliminamos el menu o productos
		Addon::where('store_id',$id)->delete();

		return redirect(env('admin').'/user')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= User::find($id);
		
		if(isset($_GET['type']) && $_GET['type'] == "trend")
		{
			$res->trending 	= $res->trending == 0 ? 1 : 0;
		}
		elseif(isset($_GET['type']) && $_GET['type'] == "open")
		{
			$res->open 	= $res->open == 0 ? 1 : 0;
		}else {
			$res->status = $res->status == 0 ? 1 : 0;
		}

		$res->save();

		return redirect(env('admin').'/user')->with('message','Status Updated Successfully.');
	}

	public function imageRemove($id)
	{
		UserImage::where('id',$id)->delete();

		return redirect::back()->with('message','Deleted Successfully.');
	}

	public function loginWithID($id)
	{
		if(Auth::loginUsingId($id))
		{
		   return Redirect::to('home')->with('message', 'Welcome ! Your are logged in now.');	
		}
		else
		{
			return Redirect::to('login')->with('error', 'Something went wrong.');
		}
		
	}

	public function ViewTime($id)
	{
		$op_time 	= new Opening_times;

		$res        = $op_time->	ViewTime($id);

		return $res;
	}

	/*
	|---------------------------------------------
	| Vista de rating de usuarios
	|--------------------------------------------
	*/

	public function rate($id)
    {
        $admin = new Admin;
		$rate  = new Rate;
		return View(
		$this->folder.'rate',
		[
			'data' 		=> User::find($id),
			'rate_data' => $rate->GetRate($id),
		]
		);
	}

	/*
	|---------------------------------------------
	| Vista de codigos QR
	|--------------------------------------------
	*/
	
	public function viewqr($id)
	{ 
		return View(
		$this->folder.'viewqr',
		[
			'data' 		=> User::find($id),
		]
		);
	}

	/*
	|---------------------------------------------
	|@Add Pay
	|---------------------------------------------
	*/

	public function pay($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('Pagos Negocios')) {

			return View(
				$this->folder.'pay',
				[
					'data' => User::find($id),
					'form_url' => env('admin').'/user_pay/'.$id,
					'link' => env('admin').'/user/',
					'currency' => Admin::find(1)->currency
				]
				);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pagos');
		}
	}

	public function payAll($id)
	{
		$stpre = User::find($id);
		$stpre->saldo = 0;
		$stpre->save();

		return Redirect::to(env('admin').'/user')->with('message', 'Saldo restablecido con exito.');
	}

	public function user_pay(Request $Request,$id)
	{
		$staff = new User;
		$new_saldo = $Request->get('new_saldo');

		$req = $staff->add_saldo($Request->All(),$id);
		if ($req == true) {
			return redirect(env('admin').'/user')->with('message','Se ha han depositado $'.number_format($new_saldo,2).' Correctamente!!');
		}else {
			return redirect(env('admin').'/user')->with('error','Algo ha ocurrido, por favor intenta nuevamente.');
		}
	}	

	public function viewmap($id)
	{
		
		return View(
			$this->folder.'google',
			[
				'data' => User::find($id),
				'form_url'  => env('admin').'/saveMap/'.$id,
				'ApiKey'     => Admin::find(1)->ApiKey_google,
			]
		);
	}

	public function saveMap(Request $Request,$id)
	{
		$data = new User;

		$req = $data->updateMap($Request->All(),$id);
		if ($req == true) {
			return redirect(env('admin').'/user/'.$id.'/edit')->with('message',"Se ha actualizado la ubicación.");
		}else {
			return redirect(env('admin').'/user/'.$id.'/edit')->with('error','Algo ha ocurrido, por favor intenta nuevamente.');
		}
	}
}