<?php



namespace App;



use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Validator;

use Auth;

use Excel;

class logs extends Authenticatable

{

    protected $table = "logs";

    /*

    |----------------------------------------------------------------

    |   Validation Rules and Validate data for add & Update Records

    |----------------------------------------------------------------

    */

    

    public function rules($type)

    {

        return [



            'name'      => 'required',



            ];

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

    |Create Registro

    |--------------------------------

    */



    public function addNew($data)

    {

        $add                    = new logs;

        $add->user_id           = isset($data['user_id']) ? $data['user_id'] : null;

        $add->store_id          = isset($data['store_id']) ? $data['store_id'] : null;

        $add->log               = isset($data['log']) ? $data['log'] : null;

        $add->view              = isset($data['view']) ? $data['view'] : null;



        $add->save();

    }



    /*

    |--------------------------------

    |Create Registro de logs

    |--------------------------------

    */



    public function getReport($data)

    {

       $res = logs::where(function($query) use($data) {

 

            if(isset($data['from']))

            {

                $from = date('Y-m-d',strtotime($data['from']));

            }

            else

            {

                $from = null;

            }

        

            if(isset($data['to']))

            {

                $to = date('Y-m-d',strtotime($data['to']));

            }

            else

            {

                $to = null;

            }

        

            if($from)

            {

                $query->whereDate('logs.created_at','>=',$from);

            }

        

            if($to)

            {

                $query->whereDate('logs.created_at','<=',$to);

            }

        

            if ($data['store'] != 0) {

                $query->where('logs.view',2);

                $query->where('logs.store_id',$data['store']);

            }

 

       })->join('app_user','logs.user_id','=','app_user.id')

         ->join('users','logs.store_id','=','users.id')

         ->select('users.name as store','app_user.name as user','logs.*')

         ->orderBy('logs.id','ASC')->get();

 

       $allData = [];

 

       foreach($res as $row)

       {

 

          $allData[] = [

          'id'     => $row->id,

          'date'   => $row->created_at,//date('d-M-Y H:M:S',strtotime($row->created_at)),

          'user'   => $row->user,

          'store'  => $row->store,

          'log'    => $row->log

          ];

       }

 

       return $allData;

    }





}

