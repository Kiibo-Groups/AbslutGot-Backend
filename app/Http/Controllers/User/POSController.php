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

class POSController extends Controller {
    
	public $folder  = "user/pos.";
	
    /*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{		
		
		return View($this->folder.'index',[
			'data' => [],
        	'user' => Auth::user(),  
			'form_url' => env('user').'/pos/',
        ]);
	} 
	
	/*
	|---------------------------------------
	|@Add new page $this->folder.'add
	|---------------------------------------
	*/
	public function show()
	{					 
		 
	}
	
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		 
		
	}
	
	/*
	|---------------------------------------
	|@Edit Page  $this->folder.'edit'
	|---------------------------------------
	*/
	public function edit($id)
	{				
		 
	}
	
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		 
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