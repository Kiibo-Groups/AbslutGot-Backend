<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Order;
use App\Exports\LogsExport;
use DB;
use Validator;
use Redirect;
use IMS;
use Excel;
class LogsController extends Controller {

	public $folder  = "admin/logs.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					
		$res = new User;
		
		return View($this->folder.'index',['data' => $res->getAll(),'form_url' => env('admin').'/exportLogs']);
	}	

	public function exportLogs(Request $Request)
	{
		if ($Request->get('type_report') == 'excel') {
			return Excel::download(new LogsExport, 'Logs.xlsx');		
		}elseif($Request->get('type_report') == 'csv') {
			return Excel::download(new LogsExport, 'Logs.csv');	
		}
	}
}