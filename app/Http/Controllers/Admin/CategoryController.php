<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\CategoryStore;
use App\User;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;
class CategoryController extends Controller {

	public $folder  = "admin/category.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$admin = new Admin;
		if($admin->hasperm('Dashboard - Categorias')){
			$res = new CategoryStore;
		
			return View($this->folder.'index',[
				'data' => $res->getAll(),
				'link' => env('admin').'/category/',
				'cats' =>  new CategoryStore
			]);

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
		$cats  = new CategoryStore;
		
		if ($admin->hasperm('Dashboard - Categorias')) {
			return View($this->folder.'add',[
				'data' => new CategoryStore,
				'form_url' => env('admin').'/category',
				'cat_p' => $cats->getCatP(),
				'cat_c' => $cats->getCatC()
			]);
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
		$data = new CategoryStore;	
		$dat  = $Request->all();
		
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		// Verificamos Categorias
		$type_cat = $dat['type_cat'];
		$flag = false;
		if ($type_cat == 0) { // Es categoria principal
			$flag = true;
		}else if ($type_cat == 1) { // Es categoria Secundaria
			// Verificamos si cuenta con una cateogira principal
			if ($dat['id_cp'] != 0) {
				$flag = true;
			}else {
				return redirect::back()->with('error','Por favor, ingresa una categoria principal');
			}
		}else if ($type_cat == 2) { // Es SubCategoria
			// Verificamos si cuenta con una cateogira principal
			if ($dat['id_cp'] != 0) {
				if ($dat['id_c'] != 0) {
					$flag = true;
				}else {
					return redirect::back()->with('error','Por favor, ingresa una categoria secundaria');
				}
			}else {
				return redirect::back()->with('error','Por favor, ingresa una categoria principal');
			}
		}

		if ($flag == true) {
			$data->addNew($Request->all(),"add"); 
			return redirect(env('admin').'/category')->with('message','New Record Added Successfully.');
		}else {
			return redirect::back()->with('error','Por favor, ingresa datos validos!!');
		}
		
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{			
		$admin = new Admin;
		$cats  = new CategoryStore;
		

		if ($admin->hasperm('Dashboard - Categorias')) {
		
		return View($this->folder.'edit',[
			'data' => CategoryStore::find($id),
			'form_url' => env('admin').'/category/'.$id,
			'cat_p' => $cats->getCatP(),
			'cat_c' => $cats->getCatC()
		]);

		} else  {
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
		$data = new CategoryStore;
		$dat  = $Request->all();
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		// Verificamos Categorias
		$type_cat = $dat['type_cat'];
		$flag = false;
		if ($type_cat == 0) { // Es categoria principal
			$flag = true;
		}else if ($type_cat == 1) { // Es categoria Secundaria
			// Verificamos si cuenta con una cateogira principal
			if ($dat['id_cp'] != 0) {
				$flag = true;
			}else {
				return redirect::back()->with('error','Por favor, ingresa una categoria principal');
			}
		}else if ($type_cat == 2) { // Es SubCategoria
			// Verificamos si cuenta con una cateogira principal
			if ($dat['id_cp'] != 0) {
				if ($dat['id_c'] != 0) {
					$flag = true;
				}else {
					return redirect::back()->with('error','Por favor, ingresa una categoria secundaria');
				}
			}else {
				return redirect::back()->with('error','Por favor, ingresa una categoria principal');
			}
		}

		if ($flag == true) {
			$data->addNew($Request->all(),$id); 
			return redirect(env('admin').'/category')->with('message','Record Updated Successfully.');
		}else {
			return redirect::back()->with('error','Por favor, ingresa datos validos!!');
		} 
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		// Verificamos si la categoria ya ha sido asignada
		$assign = User::where('type',$id);

		if ($assign->count() > 0) {
			return redirect(env('admin').'/category')->with('error','Esta categoria esta asignada a uno o varios comercios.');	
		}else {
			CategoryStore::where('id',$id)->delete();
			return redirect(env('admin').'/category')->with('message','Categoria Eliminada con exito.');
		}
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= CategoryStore::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/category')->with('message','Status Updated Successfully.');
	}
}