<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Tables;
use App\User;
use DB;
use Validator;
use Redirect;
use IMS;
class tablesController extends Controller {

	public $folder  = "user/tables.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Tables;
		
		
		if(Auth::user()->tables_opt == 0) {
			return View($this->folder.'index',[
				'data' => $res->getAll(Auth::user()->id),
				'link' => env('user').'/tables/'
			]);
		}else {
			return redirect(env('user').'/home')->with('error','No tienes permiso para acceder a la sección "Asignación de mesas".');
		}
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{								
		$u = new User;

		return View($this->folder.'add',[
            'data' => new Tables,
            'form_url' => env('user').'/tables',
            'users' => $u->getAll()
        ]);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Tables;	
	
		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		
		return redirect(env('user').'/tables')->with('message','Nueva mesa creada.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$u = new User;
		
		return View($this->folder.'edit',[
			'data' => Tables::find($id),
			'form_url' => env('user').'/tables/'.$id,
			'users' => $u->getAll()]);
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Tables;
		
		$data->addNew($Request->all(),$id);
		
		return redirect(env('user').'/tables')->with('message','Mesa actualizada con exito.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Tables::where('id',$id)->delete();

		return redirect(env('user').'/tables')->with('message','Mesa eliminada.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Tables::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('user').'/tables')->with('message','Status de la mesa cambiado.');
	}
}

