<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
class OpenpayController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // Agregamos al Cliente
    function addClient($data)
	{
		
        $fields = array(
            'name' => isset($data['name']) ? $data['name'] : '',
            'email' => isset($data['email']) ? $data['email'] : '',	
            'last_name' => isset($data['last_name']) ? $data['last_name'] : '',
            'phone' => isset($data['phone']) ? $data['phone'] : ''
        );
        
        return $this->CurlGet($fields,"<url>/app/api/addClient");
	}

    // Obtenemos el Cliente
    function getClient($data)
	{
		
        $fields = array(
            'customer' => $data['customer']
        );

        return $this->CurlGet($fields,"<url>/app/api/getClient");
    }

    // Agregamos tarjeta al cliente
    function SetCardClient($data)
    {
        return $this->CurlGet($data,"<url>/app/api/SetCardClient");
    }

    // Obtenemos listado de tarjetas
    function getCardsClient($data)
    {
        $fields = array(
            'customer' => $data['customer']
        );

        return $this->CurlGet($fields,"<url>/app/api/getCardsClient");
    }

    // Eliminamos una tarjeta
    function DeleteCard($data)
    {
        $fields = array(
            'customer' => $data['customer'],
            'cardId'   => $data['cardId']
        );

        return $this->CurlGet($data,"<url>/app/api/deleteCardClient");
    }

    // Obtenemos tarjeta unica
    function getCard($data)
    {
        $fields = array(
            'customer' => $data['customer'],
            'cardId'   => $data['cardId']
        );

        return $this->CurlGet($data,"<url>/app/api/GetCardclient");
    }

    // Generate Charge Cliente
    function chargeClient($data)
    {
        $fields = array(
            'source_id'  => $data['source_id'],
            'amount'  => $data['amount'],
            'order_id'  => $data['order_id'],
            'device_session_id'  => $data['device_session_id'],
            'customer_id' => $data['customer_id']
        );

        return $this->CurlGet($data,"<url>/app/api/chargeClient");
    }

    function CurlGet($fields,$url)
    {
        $fields = json_encode($fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $req = json_decode($response,true);

        return $req;
    }

}