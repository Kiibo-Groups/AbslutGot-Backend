<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;
use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\User;
use DB;
use Validator;
use Redirect;
use IMS;
class deliveryController extends Controller {

	public $folder  = "user/delivery.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Delivery;
		
		return View($this->folder.'index',[
			'data' => $res->getAll(Auth::user()->id),
			'link' => env('user').'/delivery/'
		]);
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{								
		$u = new User;

		return View($this->folder.'add',[
			'data' => new Delivery,
			'form_url' => env('user').'/delivery',
			'users' => $u->getAll()
		]);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Delivery;	
		
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add",'web');
		return redirect(env('user').'/delivery')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$u = new User;
		
		return View($this->folder.'edit',[
			'data' => Delivery::find($id),
			'form_url' => env('user').'/delivery/'.$id,
			'users' => $u->getAll()]);
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Delivery;
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id,'web');
		
		return redirect(env('user').'/delivery')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Delivery::where('id',$id)->delete();

		return redirect(env('user').'/delivery')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Delivery::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		// Actualizamos en el servidor
		try {
            $addServer = new NodejsServer;
            $return = array(
                'id'        => $res->id,
                'city_id'   => $res->city_id,
                'name'      => $res->name,
                'phone'     => $res->phone,
                'type_driver' => $res->type_driver,
                'max_range_km' => $res->max_range_km,
                'external_id' => $res->external_id,
                'status' => $res->status,
				'status_admin'   => $res->status_admin
            ); 
            $addServer->updateStaffDelivery($return);
        }catch (\Throwable $th) {
			
		}

		return redirect(env('user').'/delivery')->with('message','Status Updated Successfully.');
	}
}