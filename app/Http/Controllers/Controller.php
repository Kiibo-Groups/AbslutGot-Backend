<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
use Twilio;
use Pusher;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // Usuarios
    function sendPush($title,$description,$uid = 0,$img = null)
	{
		$content = ["en" => $description];
		$head 	 = ["en" => $title];		

		$daTags = [];

		if($uid > 0)
		{
			$daTags = ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $uid];
		}
		else
		{
			$daTags = ["field" => "tag", "key" => "user_id", "relation" => "!=", "value" => 'NAN'];
		}
		
		$fields = array(
			'app_id' => "app",
			'included_segments' => array('All'),	
			'filters' => [$daTags],
			'data' => array("foo" => "bar"),
			'contents' => $content,
			'headings' => $head,
			'big_picture' => $img
		);
         
		$fields = json_encode($fields);
        
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic app'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
       
	    return $response;
	}

	// Repartidores
	function sendPushD($title,$description,$uid = 0)
	{
		$content = ["en" => $description];
		$head 	 = ["en" => $title];		

		$daTags = [];

		if($uid > 0)
		{
			$daTags = ["field" => "tag", "key" => "dboy_id", "relation" => "=", "value" => $uid];
		}
		else
		{
			$daTags = ["field" => "tag", "key" => "dboy_id", "relation" => "!=", "value" => 'NAN'];
		}
		
		$fields = array(
		'app_id' => "app",
		'included_segments' => array('All'),	
		'filters' => [$daTags],
		'data' => array("foo" => "bar"),
		'contents' => $content,
		'headings' => $head
		);
        
		
		$fields = json_encode($fields);
        
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic app'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
       
	    return $response;
	} 

	// Comercios
	function sendPushS($title,$description,$uid = 0)
	{
		$content = ["en" => $description];
		$head 	 = ["en" => $title];		

		$daTags = [];

		if($uid > 0)
		{
			$daTags = ["field" => "tag", "key" => "store_id", "relation" => "=", "value" => $uid];
		}
		else
		{
			$daTags = ["field" => "tag", "key" => "store_id", "relation" => "!=", "value" => 'NAN'];
		}
		
		$fields = array(
			'app_id' => "app",
			'included_segments' => array('All'),	
			'filters' => [$daTags],
			'data' => array("foo" => "bar"),
			'contents' => $content,
			'headings' => $head
		);
		
		$fields = json_encode($fields);
        
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic app'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
       
	    return $response;
	}

	// Administrador
	function sendPushAdmin($title,$description,$uid = 0)
	{
		$content = ["en" => $description];
		$head 	 = ["en" => $title];

		$daTags = [];

		if($uid > 0)
		{
			$daTags = ["field" => "tag", "key" => "admin_id", "relation" => "=", "value" => $uid];
		}
		else
		{
			$daTags = ["field" => "tag", "key" => "admin_id", "relation" => "!=", "value" => 'NAN'];
		}

		$fields = array(
			'app_id' => "app_id",
			'included_segments' => array('All'),
			'data' => array("foo" => "bar"),
			'filters' => [$daTags],
			'contents' => $content,
			'headings' => $head,
		);


		$fields = json_encode($fields);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic token'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);

	    return $response;
	}

	// Web User
	function sendWeb($title,$description,$uid = 0)
	{ 
		$data = [
			"title" => $title,
			"message" => $description,
			'url' => "https://bincar.kiibo.mx/order?status=0" 
		]; 

		$pusher = new Pusher\Pusher(
			env('PUSHER_APP_KEY'),
			env('PUSHER_APP_SECRET'),
			env('PUSHER_APP_ID'),
			array(
				'cluster' => env('PUSHER_APP_CLUSTER'),
				'useTLS' => true
			)
		);
	 
		$response = $pusher->trigger($uid, 'new-order', $data);
		
		return $response;
	}

	// Kitchen Notify
	function sendKitchen($title,$description,$uid = 0)
	{ 
		$data = ["title" => $title,"message" => $description]; 

		$pusher = new Pusher\Pusher(
			env('PUSHER_APP_KEY'),
			env('PUSHER_APP_SECRET'),
			env('PUSHER_APP_ID'),
			array(
				'cluster' => env('PUSHER_APP_CLUSTER'),
				'useTLS' => true
			)
		);
	 
		$response = $pusher->trigger($uid, 'new-order-kitchen', $data);
		
		return $response;
	}

    public function currency()
    {
    	$admin = Admin::find(1);

    	if($admin->currency)
    	{
    		return $admin->currency;
    	}
    	else
    	{
    		return "Rs.";
    	}
    }

    public function sendSms($phone,$hash)
	{
		$sid = 'sid';
		$token = 'token';
		$client = new Client($sid, $token);

		$otp = rand(11111,99999);

		$send = $client->messages->create(
			// the number you'd like to send the message to
			"+521".$phone,
			[
				// A Twilio phone number you purchased at twilio.com/console
				'from' =>  '+13144417163',
				// the body of the text message you'd like to send
				'body' => 'Bienvenido a Prommo, ingresa el siguiente código para terminar tu registro: '.$otp.' '.$hash
			]
		);

		return $otp;
	}

	public function getLang()
	{
		$res = new Language;
		
		return $res->getAll();
	}
}