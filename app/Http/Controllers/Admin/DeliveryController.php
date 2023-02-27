<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NodejsServer;
use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\User;
use App\City;
use App\Admin;
use App\Rate;
use DB;
use Validator;
use Redirect;
use IMS;
class deliveryController extends Controller {

	public $folder  = "admin/delivery.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;
        $city = Auth::guard('admin')->user()->city_id;

		if ($admin->hasperm('Repartidores')) {
            if(Auth::guard('admin')->user()->city_id == 0){
                $res = new Delivery;

			    return View($this->folder.'index',[
				'data' => $res->getAll(0),
				'link' => env('admin').'/delivery/',
				'array'		=> [],
                'export' => env('admin').'/exportDboy/',
                'form_url' => env('admin').'/exportData_staff',
				'currency' => Admin::find(1)->currency
			]);

            }else {

                $store = 0;

                $res = Delivery::where(function($query) use($store) {

                    if($store > 0)
                    {
                        $query->where('store_id',$store);
                    }


                })->leftjoin('city','delivery_boys.city_id','=','city.id')
                  ->select('city.name as city','delivery_boys.*')
                  ->where('city_id', "$city")->paginate(10);


                return View($this->folder.'index',[
                    'data' => $res,
                    'link' => env('admin').'/delivery/',
                    'array'		=> [],
                    'export' => env('admin').'/exportDboy/',
                    'form_url' => env('admin').'/exportData_staff'
                ]);

            }


		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

	public function report_dboy($id)
	{

		$admin = new Admin;

		if ($admin->hasperm('Repartidores')) {
			$res = new Delivery;
			return View($this->folder.'report',[
				'data' => $res->getReport($id),
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
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

		if ($admin->hasperm('Repartidores')) {
			$u = new User;
			$city = new City;
			return View($this->folder.'add',[
				'data' => new Delivery,
				'form_url' => env('admin').'/delivery',
				'citys'    => $city->getAll(0),
				'users' => $u->getAll()
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{
		$data = new Delivery;

		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add",'web');

		return redirect(env('admin').'/delivery')->with('message','Nuevo Repartidor agregado...');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('Repartidores')) {
			$u = new User;
			$city = new City;
			return View(
				$this->folder.'edit',
				[
					'data' => Delivery::find($id),
					'form_url' => env('admin').'/delivery/'.$id,
					'users' => $u->getAll(),
					'citys'    => $city->getAll(0),
					]
				);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

    public function pay($id)
    {
        $admin = new Admin;

		if ($admin->hasperm('Repartidores')) {
			return View(
				$this->folder.'pay',
				[
					'data' => Delivery::find($id),
					'form_url' => env('admin').'/delivery_pay/'.$id,
					'link' => env('admin').'/delivery/',
					'currency' => Admin::find(1)->currency
				]
			);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

	public function payAll($id)
	{
		$staff = Delivery::find($id);
		$staff->amount_acum = 0;
		$staff->save();

		return Redirect::to(env('admin').'/delivery')->with('message', 'Saldo restablecido con exito.');
	}

	public function rate($id)
    {
        $admin = new Admin;
		$rate  = new Rate;
		return View(
		$this->folder.'rate',
		[
			'data' 		=> Delivery::find($id),
			'rate_data' => $rate->GetRate($id),
			]
		);
	}
	
	public function delivery_pay(Request $Request,$id)
	{
		$staff = new Delivery;

		$req = $staff->add_comm($Request->All(),$id);

		if ($req == 'mount_sup') {
			return redirect::back()->with('message','El monto es mayor al adeudo.');
		}else {
			return redirect(env('admin').'/delivery')->with('message','Pago realizado con exito.');	
		}
		
		
	}	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{
		$data = new Delivery;

		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id,'web');

		return redirect(env('admin').'/delivery')->with('message','Record Updated Successfully.');
	}

	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Delivery::where('id',$id)->delete();

		return redirect(env('admin').'/delivery')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Delivery::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		// Actualizamos en el servidor
		try {
            $addServer = new NodejsServer;
            $return = array(
                'id'        => $res->id,
                'city_id'   => $res->city_id,
                'name'      => $res->name,
                'phone'     => $res->phone,
                'type_driver' => $res->type_driver,
                'max_range_km' => $res->max_range_km,
                'external_id' => $res->external_id,
                'status' => $res->status,
				'status_admin'   => $res->status_admin
            );
            
            $addServer->updateStaffDelivery($return);
        }catch (\Throwable $th) {
			
		}

		return redirect(env('admin').'/delivery')->with('message','Status Updated Successfully.');
	}

	public function status_admin($id)
	{
		$res 			= Delivery::find($id);
		$res->status_admin 	= $res->status_admin == 0 ? 1 : 0;
		$res->save();

		// Actualizamos en el servidor
		try {
            $addServer = new NodejsServer;
            $return = array(
                'id'        => $res->id,
                'city_id'   => $res->city_id,
                'name'      => $res->name,
                'phone'     => $res->phone,
                'type_driver' => $res->type_driver,
                'max_range_km' => $res->max_range_km,
                'external_id' => $res->external_id,
                'status' => $res->status,
				'status_admin'   => $res->status_admin
            );
            
            $addServer->updateStaffDelivery($return);
        }catch (\Throwable $th) {
			
		}

		if ($res->status_admin == 1) {
			return redirect(env('admin').'/delivery')->with('message','El Repartidor ha sido bloqueado.');
		}else {
			return redirect(env('admin').'/delivery')->with('message','El Repartidor esta activo.');
		}
	}

	public function getCity($id)
	{
		$res = User::find($id);
		return $res->name;
	}

	/*
	|---------------------------------------
	|@View Report
	|---------------------------------------
	*/
	public function exportDboy($id)
	{
		return Excel::download(new DeliveryExport($id), 'report.xlsx');
	}
}
