<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Banner;
use App\City;
use App\User;
use App\Text;
use App\Language;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;
class TextController extends Controller {

	public $folder  = "admin/text.";
	
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function show()
	{		
		$admin = new Admin;

		if ($admin->hasperm('Dashboard - Textos de la aplicacion')) {
		
		$lang = new Language;

		return View($this->folder.'index',[

			'form_url'  => env('admin').'/text',
			'data' 		=> new Text,
			'langs' 	=> $lang->getWithEng()

			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Textos de la aplicacion');
		}
	}	
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Text;	
		
		$data->addNew($Request->all());
		
		return redirect::back()->with('message','Updated Successfully.');
	}
}