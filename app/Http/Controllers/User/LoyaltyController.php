<?php 

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Loyalty; 
use App\AppUser;
use App\Item;
use DB;
use Validator;
use Redirect;
use IMS;

class LoyaltyController extends Controller {
    
	public $folder  = "user/loyalty.";
	
    /*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{		
		$res = new Loyalty; 
		$item = new Item;
		$count_its = Loyalty::where('store_id',Auth::user()->id)->first();

		return View($this->folder.'index',[
			'data' => $res->getAll(),
            'items' => $item->getAll(),
            'arrayItems' => ($count_its) ? $res->getItems() : [],
			'user' => Auth::user(),  
			'form_url' => env('user').'/loyalty/',
        ]);
	} 
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{					 
		$res = new Loyalty; 
		$item = new Item; 

		return View($this->folder.'add',[
			'data' => new Loyalty,
			'items' => $item->getAll(),
            'arrayItems' => [],
			'user' => Auth::user(),  
			'form_url' => env('user').'/loyalty/',
		]);
	}
	
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Loyalty;	

		$data->addNew($Request->all(),"add");
		return redirect(env('user').'/loyalty')->with('message','New Record Added Successfully.');
		
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$res = new Loyalty; 
		$item = new Item;
		$count_its = Loyalty::where('store_id',Auth::user()->id)->first();

		return View(
			$this->folder.'edit',[
				'data' => Loyalty::find($id),
				'items' => $item->getAll(),
				'arrayItems' => ($count_its) ? $res->getItems() : [],
				'user' => Auth::user(),   
				'form_url' => env('user').'/loyalty/'.$id
		]);
	}
	
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Loyalty;
		
		$data->addNew($Request->all(),$id);
		
		return redirect(env('user').'/loyalty')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		 
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		 
	}
}