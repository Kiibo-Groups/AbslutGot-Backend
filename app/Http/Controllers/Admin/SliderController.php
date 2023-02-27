<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Slider;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;
class SliderController extends Controller {

	public $folder  = "admin/slider.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{		
		$admin = new Admin;

		if ($admin->hasperm('Banners')) {			
		$res = new Slider;
		
		return View($this->folder.'index',['data' => $res->getAll(),'link' => env('admin').'/slider/']);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Banners');
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

		if ($admin->hasperm('Banners')) {					
		return View($this->folder.'add',['data' => new Slider,'form_url' => env('admin').'/slider']);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Banners');
		}
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Slider;	
		
		$data->addNew($Request->all(),"add");
		
		return redirect(env('admin').'/slider')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		
		$admin = new Admin;

		if ($admin->hasperm('Banners')) {
		return View($this->folder.'edit',['data' => Slider::find($id),'form_url' => env('admin').'/slider/'.$id]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Banners');
		}
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Slider;
		
		$data->addNew($Request->all(),$id);
		
		return redirect(env('admin').'/slider')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		$res = Slider::find($id);

		unlink("upload/slider/".$res->img);

		$res->delete();

		return redirect(env('admin').'/slider')->with('message','Record Deleted Successfully.');
	}
}