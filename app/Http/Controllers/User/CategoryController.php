<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Item;
use App\Addon;
use DB;
use Validator;
use Redirect;
use IMS;
class CategoryController extends Controller {

	public $folder  = "user/category.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Category;
		
		return View($this->folder.'index',[
			'data' => $res->getAll(),
			'user' => Auth::user(),
			'name' => '',
			'type' => 0,
			'link' => env('user').'/category/']);
	}	

	public function search(Request $request){
		$name = $request->get('name');
		$type = (null !== $request->get('type')) ? $request->get('type') : 0;

		$data = Category::where('type',$type)->where('store_id',Auth::user()->id)->where(function($query) use($name,$type){

			$query->whereRaw('lower(name) like "%'. strtolower($name) . '%"')
			->orwhereRaw('lower(id_element) like "%'. strtolower($name) . '%"');


		})->orderBy('sort_no','ASC')->paginate(10);

		return view($this->folder.'index',[
			'data' => $data,
			'name' => $name,
			'user' => Auth::user(),
			'type' => $type,
			'link' => env('user').'/category/']);
		
    }
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{								
		return View(
			$this->folder.'add',
			[
				'data' => new Category,
				'user' => Auth::user(),
				'stat'  => 'new',
				'form_url' => env('user').'/category'
			]
		);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Category;	

		$ban = false;
		if (isset($Request->description)) {
			$ban = true;
		}elseif(isset($Request->name)) {
			$ban = true;
		}

		if ($ban == true) {
			$data->addNew($Request->all(),"add");
			return redirect(env('user').'/category')->with('message','New Record Added Successfully.');
		}else {
			return redirect(env('user').'/category/add')->with('error','Ingresa todos los campos.');
		}
		
		
		
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		return View(
			$this->folder.'edit',
			['data' => category::find($id),
			'user' => Auth::user(),
			'stat'  => 'edit',
			'form_url' => env('user').'/category/'.$id]
		);
	}
	
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Category;
		
		$data->addNew($Request->all(),$id);
		
		return redirect(env('user').'/category')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		// Verificamos si la categoria ya ha sido asignada
		$assign = Item::where('category_id',$id);
		$assign_addon = Addon::where('category_id',$id);

		if ($assign->count() > 0) {
			return redirect(env('user').'/category')->with('error','Esta categoria esta asignada a uno o varios productos.');	
		}else {
			if ($assign_addon->count() > 0) {
				return redirect(env('user').'/category')->with('error','Esta categoria esta asignada a uno o varios Complementos.');				
			}else {
				Category::where('id',$id)->delete();
				return redirect(env('user').'/category')->with('message','Categoria Eliminada con exito.');
			}
		}
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Category::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('user').'/category')->with('message','Status Updated Successfully.');
	}
}