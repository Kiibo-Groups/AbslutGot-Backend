<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Addon;
use App\Category;
use DB;
use Validator;
use Redirect;
use IMS;
class AddonController extends Controller {

	public $folder  = "user/addon.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Addon;
		
		return View($this->folder.'index',[
			'data' => $res->getAll(),
			'link' => env('user').'/addon/',
			'name' 		=> '',
			'cate' 		=> '',
			'category' => Category::where('store_id',Auth::user()->id)->where('type',1)->get(),
			'c' => $this->currency()]);
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{						
		$cate = new Category;		
		return View($this->folder.'add',[
			'data' 		=> new Addon,
			'cates' 	=>  Category::where('store_id',Auth::user()->id)->where('type',1)->get(), //$cate->getAll(),
			'form_url' 	=> env('user').'/addon']);
	}
	

	public function search_addon(Request $request){
		$name = $request->get('name');
		$cate = $request->get('cate');

		$data = Addon::where(function($query) use($name,$cate){

			$query->whereRaw('lower(addon.name) like "%'. strtolower($name) . '%"')
			->orWhereRaw('lower(category.name) like "%'. strtolower($name) . '%"');

		})->join('category','addon.category_id','=','category.id')
		->select('addon.*','category.name as cate','category.id_element as id_element')
		->where('addon.category_id',$cate)
		->where('addon.store_id',Auth::user()->id)
        ->orderBy('addon.id','DESC')->paginate(10);
	
		return view($this->folder.'index',[
			'data' => $data,
			'name' => $name,
			'cate' => $cate,
			'link' => env('user').'/addon/',
			'category' => Category::where('store_id',Auth::user()->id)->where('type',1)->get(),
			'c' => $this->currency()
		]);
		
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Addon;	
		
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}
		
		$data->addNew($Request->all(),"add");
		
		return redirect(env('user').'/addon')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$cate = new Category;	
		return View($this->folder.'edit',[
			'data' => Addon::find($id),
			'cates' 	=> $cate->getAll(),
			'form_url' => env('user').'/addon/'.$id]);
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Addon;
		
		$data->addNew($Request->all(),$id);
		
		return redirect(env('user').'/addon')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Addon::where('id',$id)->delete();

		return redirect(env('user').'/addon')->with('message','Record Deleted Successfully.');
	}
}