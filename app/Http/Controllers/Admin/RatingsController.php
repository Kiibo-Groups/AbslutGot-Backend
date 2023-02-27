<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Banner;
use App\City;
use App\User;
use App\Delivery;
use App\Rate;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;
class RatingsController extends Controller {

	public $folder  = "admin/ratings.";
	
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{		
		$admin = new Admin;

		if ($admin->hasperm('Ratings')) {
		
            $admin = new Admin;
            $rate  = new Rate;
            return View(
            $this->folder.'index',
            [
                'data' 		=> [], //Delivery::find($id),
                'rate_data' => [] //$rate->GetRate($id),
            ]);

		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Calificaciones');
		}
	}	
	
}