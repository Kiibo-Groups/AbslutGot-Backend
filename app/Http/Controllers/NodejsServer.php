<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
class NodejsServer extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 
     * Integracion de Pedidos y Seguimiento 
     * 
    */

    function newOrder($data)
    {
        return $this->CurlGet($data,"<url>/app/api/newOrder/");
    }

    function orderStatus($data)
    {
        return $this->CurlGet($data,"<url>/app/api/orderStatus/");
    }
    
    function setStaffDelivery($data)
    {
        return $this->CurlGet($data,"<url>/app/api/setStaffDelivery/");
    }

    function delStaffDeliveryOrder($data)
    {
        return $this->CurlGet($data,"<url>/app/api/delStaffDeliveryOrder/");
    }

    /**
     * 
     * Integracion de agregado de repartidores 
     * 
    */
    function newStaffDelivery($data)
    {
        return $this->CurlGet($data,"<url>/app/api/newStaff/");
    }

    function updateStaffDelivery($data)
    {
        return $this->CurlGet($data,"<url>/app/api/updateStaff/");
    }

    /**
     * 
     * Integracion de mandaditos 
     * 
    */

    // Realizamos la peticion de nuevo mandadito
    function NewOrderComm($data)
    {
        $fields = array(
            'id_order' => isset($data['id_order']) ? $data['id_order'] : ''
        );
    
        return $this->CurlGet($fields,"<url>/app/api/newOrderComm/");
    }

    // Cron para termino del pedido
    function notifyClient($data)
    {
        $fields = array(
            'id_order' => isset($data['order_id']) ? $data['order_id'] : 0
        );
        
        return $this->CurlGet($fields,"<url>/app/api/InitCronOrder/");
    }
    
    
    /**
     * Realizamos la peticion
     */
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
