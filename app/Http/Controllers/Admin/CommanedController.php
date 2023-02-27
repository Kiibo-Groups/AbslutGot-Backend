<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\City;
use App\Delivery;
use App\Admin;
use App\Order_staff;
use App\Commaned;
use App\AppUser;
use DB;
use Validator;
use Redirect;
use IMS;
class CommanedController extends Controller {

	public $folder  = "admin/commaned.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new Commaned;
        $admin = new Admin;
        $status = 0;
        $title = 'Listado de Servicios';
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
			if($_GET['status'] == 0)
			{
				$title = "Nuevos Servicios";
			}
			elseif($_GET['status'] == 1)
			{
				$title = "Servicios en ejecución";
			}
			elseif($_GET['status'] == 2)
			{
				$title = "Servicios cancelados";
			}
            elseif($_GET['status'] == 3)
			{
				$title = "Servicios no asignados";
			}
			elseif($_GET['status'] == 6)
			{
				$title = "Servicios finalizados";
			}	
		}

		if ($admin->hasperm('Gestion de pedidos')) {
            return View($this->folder.'index',[
                'data' 		=> $res->getAll($status),
				'comm_f'    => new Commaned,
                'link' 		=> env('admin').'/commaned/',
                'title' 	=> $title,
                'status'    => $status,
                'currency'	=> Admin::find(1)->currency
            ]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Servicios');
		}	
	}	

	public function commanedStatus()
	{
        try {
            $res 				= Commaned::find($_GET['id']);
            if($_GET['status'] == 0){ // Solicitud de Reasignacion
                $res->status 		= $_GET['status'];		
                $res->save();
                // Comenzamos la solicitud de repartidores
                $req = new NodejsServer;
                $data = [
                    'id_order' => $res->id
                ];
                
                $req->NewOrderComm($data);
            }else if ($_GET['status'] == 2) {
                Order_staff::where('event_id',$_GET['id'])->delete();
                $res->status 		= $_GET['status'];
                $res->save();
                // Notificamos
                $msg = "Lamentablemente tu pedido ha sido cancelado.";
                $title = "Pedido Cancelado.";
                app('App\Http\Controllers\Controller')->sendPush($title,$msg,$res->user_id);
            }
        
            return Redirect::back()->with('message','Status del servicio cambiado con exito.'); 
        } catch (\Throwable $th) {
            return Redirect::back()->with('error','Ha ocurrido un problema interno'); 
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
		$res   = new Commaned;

		if ($admin->hasperm('Servicios')) {
		
			return View($this->folder.'add',[ 
				'data' 		=> new Commaned,
				'users'     => AppUser::where('status',0)->get(),
				'citys'     => City::where('status',0)->get(),
				'form_url' 	=> env('admin').'/commaned',
				'array'		=> [],
				'admin'     => Admin::find(1),
				'res'       => $res
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Servicios');
		}
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new Commaned;

		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		return redirect(env('admin').'/commaned')->with('message','Nuevo servicio agregado.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{
		$admin = new Admin;
		$res   = new Commaned;

		if ($admin->hasperm('Gestion de pedidos')) {
			return View($this->folder.'edit',[
				'data' 		=> $res->getElement($id),
				'form_url' 	=> env('admin').'/Services/'.$id,
				'admin'     => Admin::find(1),
				'res'       => $res
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Adminisrtar Restaurantes');
		}
	}
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new Commaned;
		$data->updateComm($Request->all(),$id);

		return redirect(env('admin').'/Services')->with('message','Servicio actualizado con exito.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Commaned::where('id',$id)->delete();

		return redirect(env('admin').'/Services')->with('message','Elemento eliminado');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Commaned::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/Services')->with('message','Status Updated Successfully.');
	}
}