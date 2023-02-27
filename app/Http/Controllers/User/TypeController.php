<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Type;
use DB;
use Validator;
use Redirect;
use IMS;
class TypeController extends Controller {

	public $folder  = "user/type.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Type;
		
		return View($this->folder.'index',['data' => $res->getAll(),'link' => env('user').'/type/']);
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{								
		return View($this->folder.'add',['data' => new Type,'form_url' => env('user').'/type']);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Type;	
		
		$data->addNew($Request->all(),"add");
		
		return redirect(env('user').'/type')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		return View($this->folder.'edit',['data' => Type::find($id),'form_url' => env('user').'/type/'.$id]);
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Type;
		
		$data->addNew($Request->all(),$id);
		
		return redirect(env('user').'/type')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Type::where('id',$id)->delete();

		return redirect(env('user').'/type')->with('message','Record Deleted Successfully.');
	}
}