<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Item;
use App\Type;
use App\Addon;
use App\ItemAddon;
use App\Text;
use App\Exports\ItemExport;
use DB;
use Validator;
use Redirect;
use IMS;
use Excel;
class ItemController extends Controller {

	public $folder  = "user/item.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res 	= new Item;
		$addon  = new Addon;
		
		return View($this->folder.'index',[

			'data' 	=> $res->getAll(),
			'link' 	=> env('user').'/item/',
			'addon' => $addon->getAll(),
			'name' => '',
			'cate' => '',
			'category' => Category::where('store_id',Auth::user()->id)->where('type',0)->get(),
			'assign' => new ItemAddon

			]);
	}	
	
	public function search_item(Request $request){
		$name = $request->get('name');
		$cate = $request->get('cate');

		$data = Item::where('category_id',$cate)->where('store_id',Auth::user()->id)->where(function($query) use($name,$cate){

			$query->whereRaw('lower(name) like "%'. strtolower($name) . '%"')
			->orwhereRaw('lower(description) like "%'. strtolower($name) . '%"');

		})->orderBy('item.id','DESC')->paginate(10);

		$res 	= new Item;
		$addon  = new Addon;
		return view($this->folder.'index',[
			'data' => $data,
			'name' => $name,
			'cate' => $cate,
			'link' 	=> env('user').'/item/',
			'addon' => $addon->getAll(),
			'category' => Category::where('store_id',Auth::user()->id)->where('type',0)->orderBy('sort_no','DESC')->get(),
			'assign' => new ItemAddon
		]);
		
    }

	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{								
		$cate = new Category;
		$type = new Type;
		$add  = new Addon;
		$text = new Text;
		return View($this->folder.'add',[
			'data' 		=> new Item,
			'user' 		=> Auth::user(),
			'text'		=> $text->getAppData(0),
			'form_url' 	=> env('user').'/item',
			'cates' 	=> Category::where('store_id',Auth::user()->id)->where('type',1)->get(),
			'type'		=> $type->getAll(),
			'addons'    => $add->getAll(),
			'array'		=> [],
			'arrayCate'	=> [],
			'category' => Category::where('store_id',Auth::user()->id)->where('type',0)->get(),
		]);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Item;	
		
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		
		return redirect(env('user').'/item')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$cate = new Category;
		$type = new Type;
		$addons = new Addon;
		$add  = new ItemAddon;
		$text = new Text;
		return View($this->folder.'edit',[
			'data' 		=> Item::find($id),
			'user' 		=> Auth::user(),
			'form_url' 	=> env('user').'/item/'.$id,
			'text'		=> $text->getAppData(0),
			'cates' 	=> Category::where('store_id',Auth::user()->id)->where('type',1)->get(), //$cate->getAll(),
			'type'		=> $type->getAll(),
			'addons'    => $addons->getAll(),
			'arrayCate'	=> $add->getAssignedCate($id),
			'array'     => $add->getAssigned($id),
			'category' => Category::where('store_id',Auth::user()->id)->where('type',0)->get(),
		]);
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Item;
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id);
		
		return redirect(env('user').'/item')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Item::where('id',$id)->delete();
		ItemAddon::where('item_id',$id)->delete();

		return redirect(env('user').'/item')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Item::find($id);
		
		if(isset($_GET['type']) && $_GET['type'] == "trend")
		{
			$res->trending 	= $res->trending == 0 ? 1 : 0;
		}
		else {
			$res->status = $res->status == 0 ? 1 : 0;
		}

		$res->save();


		return redirect(env('user').'/item')->with('message','Status actualizado con exito.');
	}

	public function addon(Request $Request)
	{
		$data = new ItemAddon;

		$data->addNew($Request->all(),$Request->item_id);

		return redirect::back()->with('message','Updated Successfully.');
	}

	public function export()
	{
		return Excel::download(new ItemExport, 'items.xlsx');
	}

	public function import()
	{
		return View($this->folder.'import');
	}

	public function _import(Request $Request)
	{
		$res = new Item;

		$res->import($Request->all());

		return Redirect::back()->with('message','Uploaded Successfully.');
	}
}