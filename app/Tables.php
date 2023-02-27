<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;

use QrCode;
class Tables extends Authenticatable
{
    protected $table = "tables";

     /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */

    public function rules($type)
    {
        if($type === 'add')
        {
            return [

            'mesa' => 'required|unique:tables',

            ];
        }
        else
        {
            return [

            'mesa'     => 'required|unique:tables,mesa,'.$type,

            ];
        }
    }

    public function validate($data,$type)
    {

        $validator = Validator::make($data,$this->rules($type));
        if($validator->fails())
        {
            return $validator;
        }
    }

    /*
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($data,$type)
    {
        $user                   = Auth::user();
        $add                    = $type === 'add' ? new Tables : Tables::find($type);

        if ($type != 'add') {
            $req_table = Tables::where('mesa',$data['mesa'])->first();
            if ($req_table) {
                return false;
            }
        }
        $add->store_id          = $user->id;
        $add->mesa              = isset($data['mesa']) ? $data['mesa'] : 0;
        $add->descript          = isset($data['descript']) ? $data['descript'] : '';

        // Generamos link y QR
        $link               = "https://".$user->dominio.".kiibo.mx/home?mesa=".$add->mesa;
        $codeQR             = base64_encode(QrCode::format('png')->size(200)->generate($link));
        
        $add->link              = $link;
        $add->qr                = $codeQR;
        $add->status            = isset($data['status']) ? $data['status'] : 0; 
        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($store = 0)
    {
        return Tables::where(function($query) use($store) {

            if($store > 0)
            {
                $query->where('store_id',$store);
            }

        })->get();
    }


    /*
    |--------------------------------------
    |Get Report
    |--------------------------------------
    */
    public function getReport($data)
    {
        $res = Delivery::where(function($query) use($data) {

            if($data['staff_id'])
            {
                $query->where('delivery_boys.id',$data['staff_id']);
            }

        })->join('orders','delivery_boys.id','=','orders.d_boy')
        ->select('orders.store_id as ord_store_id','orders.*','delivery_boys.*')
        ->orderBy('delivery_boys.id','ASC')->get();

       $allData = [];

       foreach($res as $row)
       {

            // Obtenemos el comercio
            $store = User::find($row->ord_store_id);

            $allData[] = [
                'id'                => $row->id,
                'name'              => $row->name,
                'rfc'               => $row->rfc,
                'email'             => $row->email,
                'store'             => $store->name,
                'store_rfc'         => $store->rfc,
                'platform_porcent'  => $row->price_comm,
                'type_staff_porcent'=> ($row->c_type_staff == 0) ? 'Valor Fijo' : 'valor en %',
                'staff_porcent'     => $row->c_value_staff,
                'total'             => $row->total
            ];
       }

       return $allData;
    }


}
