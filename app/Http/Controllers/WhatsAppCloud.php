<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
class WhatsAppCloud extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $Token;

    /**
     * 
     * Generacion de Constructor
     * 
     */
    public function __construct()
    {
        $this->Token = "EAAJW0jeXf18BAIziZAGpWTcJrSJcJ0nZAy3jyy0PeO3pf4kLom35IiVXtbuU8a90e1JLTu4M4SBAByw7KjTA2zZBC4lN4FitODWVnBBkcXb27TTYl7ASyXS17ZCQJNG22yoxdtRzwZB3Bt4tPiHbrSFf9KME38P9HFuF5ky6gwd9Q7LSvQNsDLcIQ6dsQBbGpY7bBOZBT3s0cHk0DCMkZBbVfoPsXoaUGMZD";
    }

    // Enviamos MSG
    function SendMessage()
	{
		$fields = array(
            'messaging_product' => 'whatsapp',
            'to'    => '526361229546',// 528121067435 / 526361229546
            'type'  => 'template',
            'template' => array(
                'name' => 'sample_issue_resolution',
                'language' => array(
                    'code' => 'es'
                ),
                "components" => array(
                    array(
                        "type" => "body",
                        "parameters" => array(
                            array(
                                "type" => "text",
                                "text" => "Adrian Quezada Figueroa"
                            )
                        )
                    )
                )
            ), 
        );

        
 
        return $this->CurlGet($fields,"https://graph.facebook.com/v13.0/105545148849246/messages/");
	
    
    }


    function CurlGet($fields,$url)
    {
        $fields = json_encode($fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Bearer '.$this->Token));
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