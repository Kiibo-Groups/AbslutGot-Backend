<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Language;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;
class LanguageController extends Controller {

	public $folder  = "admin/language.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;
		if($admin->hasperm('Dashboard - Categorias')){

		$res = new Language;

		return View($this->folder.'index',['data' => $res->getAll(),'link' => env('admin').'/language/']);

	} else {
		return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Categorias');
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

		if ($admin->hasperm('Dashboard - Categorias')) {
		return View($this->folder.'add',['data' => new Language,'form_url' => env('admin').'/language']);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Categorias');
		}
	}

	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{
		$data = new Language;

		$data->addNew($Request->all(),"add");

		return redirect(env('admin').'/language')->with('message','New Record Added Successfully.');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('Dashboard - Categorias')) {
		return View($this->folder.'edit',['data' => Language::find($id),'form_url' => env('admin').'/language/'.$id]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Categorias');
		}
	}

	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{
		$data = new Language;

		$data->addNew($Request->all(),$id);

		return redirect(env('admin').'/language')->with('message','Record Updated Successfully.');
	}

	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Language::where('id',$id)->delete();

		return redirect(env('admin').'/language')->with('message','Record Deleted Successfully.');
	}
}
