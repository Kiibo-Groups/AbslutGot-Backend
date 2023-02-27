<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Admin;
use App\City;
use DB;
use Illuminate\Support\Facades\Redirect as FacadesRedirect;
use Validator;
use Redirect;
use IMS;
class AdminUserController extends Controller {

	public $folder  = "admin/adminUser.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$admin = new Admin;

		if($admin->hasperm('Subaccount')){
			return View($this->folder.'index',[
				'data' => $admin->getAll(),
				'link' => env('admin').'/adminUser/'
				]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tiene permiso de ver la seccion SubCuentas');
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

		if ($admin->hasperm('Subaccount')) {
            $city = new City;

			return View($this->folder.'add',[

                'data' => new Admin,
                'citys'     => $city->getAll(0),
				'form_url' => env('admin').'/adminUser',
				'array'		=> []

			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección SubCuentas');
		}
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Admin;	
		
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		
		return redirect(env('admin').'/adminUser')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$admin = new Admin;

		if ($admin->hasperm('Subaccount')) {
            $admin = Admin::find($id);
            $city = new City;

			return View($this->folder.'edit',[

            'data' 		=> $admin,
            'citys'     => $city->getAll(0),
			'form_url'  => env('admin').'/adminUser/'.$id,
			'array'		=> explode(",", $admin->perm)

			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección SubCuentas');
		}
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Admin;
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id);
		
		return redirect(env('admin').'/adminUser')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Admin::where('id',$id)->delete();

		return redirect(env('admin').'/adminUser')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Admin::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/adminUser')->with('message','Status Updated Successfully.');
	}
}