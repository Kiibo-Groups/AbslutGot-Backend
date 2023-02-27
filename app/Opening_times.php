<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DateTime;
class Opening_times extends Authenticatable
{
    protected $table = 'opening_times';

    public function getAll($id)
    {
        $times = Opening_times::where('store_id',$id)->get();
        
        if ($times->count() > 0) {
            return $times;
        }else {
            return $this->chkTimes();
        }
    }

    public function chkTimes()
    {
        $chk = Opening_times::where('store_id',0)->get();
        $times = [];

        if ($chk->count() > 0) {
            return $chk;
        }else {
            //Add Times Week
            $add        = new Opening_times;
            $add->store_id  = 0;
            $add->mon       = '00:00 AM - 00:00 PM';
            $add->tue       = '00:00 AM - 00:00 PM';
            $add->wed       = '00:00 AM - 00:00 PM';
            $add->thu       = '00:00 AM - 00:00 PM';
            $add->fri       = '00:00 AM - 00:00 PM';
            $add->sat       = '00:00 AM - 00:00 PM';
            $add->sun       = '00:00 AM - 00:00 PM';
    
            $add->save();

            return $this->chkTimes();
        }
    }

    public function addNew($data,$type)
    {
        // $item            = $type === 'add' ? new Opening_times : Opening_times::where('store_id',$type)->get();
        
        $item               = Opening_times::where('store_id',$type)->get();
        
        if ($item->count() > 0) {
          foreach ($item as $key) {
            $add         = Opening_times::find($key->id);
          }
        }else {
            $add        = new Opening_times;
        }

    
        $add->store_id  = $type;
        $add->mon       = $data[0]['mon'];
        $add->tue       = $data[0]['tue'];
        $add->wed       = $data[0]['wed'];
        $add->thu       = $data[0]['thu'];
        $add->fri       = $data[0]['fri'];
        $add->sat       = $data[0]['sat'];
        $add->sun       = $data[0]['sun'];

        $add->save();

        return ['msg' => 'done'];
    }

    public function Remove($id)
    {
       return Opening_times::where('id',$id)->delete();
    }

    public function ViewTime($id)
	{
        $times = 0;
        $isOpen = 0;
        $w_close = 0;
        $isTime = 0;
        $now = 0;
        if ($this->GetTimes($id)) {
            $times = $this->GetTimes($id)[0];
            $now =  strtotime(date('H:i'));
            $isTime = $this->GetDay($times);

            if ($isTime != 'closed') {
                
                $isTimes_1 = explode(' - ',$isTime);
            
                $open = new DateTime($isTimes_1[0]);
                $close = new DateTime($isTimes_1[1]);
                $query_time = new DateTime(date("H:i"));
                //Determine if open time is before close time in a 24 hour period
                if($open < $close){
                    //If so, if the query time is between open and closed, it is open
                    if($query_time > $open){
                        if($query_time < $close){
                            $isOpen = strtotime($isTimes_1[1]) - $now;
                        }
                    }
                }
                elseif($open > $close){
                    $isOpen = strtotime($isTimes_1[1]) - $now;
                    //If not, if the query time is between close and open, it is closed
                    if($query_time < $open){
                        if($query_time > $close){
                            $isOpen = 0;
                        }
                    }
                }
                
                if ($isOpen > 0) {
                    if (ceil($isOpen/60) <= 20) { // Si faltan 20 Minutos para cerrar enviamos el tiempo
                        $w_close = ceil($isOpen/60);
                    }   
                }
            }
        }
        
        

		return [
            'status' => $isOpen, // Is Open or Close
            'Time' => date('H:i'), // Time Serve
            "w_close" => $w_close, // If IsOpen how much is left to close
            'times' => $this->GetDay($times), // Day of Week
            'now'   => $now
            
        ];
    }
    
    public function compileHours($times, $timestamp) {
            $times = $times[strtolower(date('D',$timestamp))];
            if(!strpos($times, '-')) return array();
            $hours = explode(",", $times);
            $hours = array_map('explode', array_pad(array(),count($hours),'-'), $hours);
            $hours = array_map('array_map', array_pad(array(),count($hours),'strtotime'), $hours, array_pad(array(),count($hours),array_pad(array(),2,$timestamp)));
            end($hours);
            if ($hours[key($hours)][0] > $hours[key($hours)][1]) $hours[key($hours)][1] = strtotime('+1 day', $hours[key($hours)][1]);
            return $hours;
    }

    public function isOpen($now, $times) {
            $open = 0; // time until closing in seconds or 0 if closed
            // merge opening hours of today and the day before
            $hours = array_merge($this->compileHours($times, strtotime('yesterday',$now)),$this->compileHours($times, $now)); 

            foreach ($hours as $h) {
                
                if ($now >= $h[0] and $now < $h[1]) {
                    $open = $h[1] - $now;
                    return $open;
                } 
            }
            return $open;

           
    }

    public function GetTimes($id)
    {
        $storeSchedule = Opening_times::where('store_id',$id)->get();
        $times = array();

        if ($storeSchedule->count() > 0) {
            foreach ($storeSchedule as $key) {
                array_push($times,[
                    'mon' => $key['Mon'],
                    'tue' => $key['Tue'],
                    'wed' => $key['Wed'],
                    'thu' => $key['Thu'],
                    'fri' => $key['Fri'],
                    'sat' => $key['Sat'],
                    'sun' => $key['Sun']
                ]);
            }
        }

        return $times;
    }
    
    public function GetDay($times)
    {
        $day = date('N');

        switch ($day) {
            case 1:
                return $times['mon'];
                break;
            case 2:
                return $times['tue'];
                break;
            case 3:
                return $times['wed'];
                break;
            case 4:
                return $times['thu'];
                break;
            case 5:
                return $times['fri'];
                break;
            case 6:
                return $times['sat'];
                break;
            case 7:
                return $times['sun'];
                break;
            default:
                return $times['mon'];
                break;
        }
    }

    public function ViewTimeDate($data,$day)
    {
        $date = $data[0][$day];
        $open_time   = '';
        $close_time  = '';

        if ($date == 'closed') {
            $open_time  = "00:00";
            $am_pm_open = 'AM'; 
    
            $close_time  = "00:00";
            $am_pm_close = "AM"; 
        }else {
            // '11:00 - 22:00'
            $res = explode('-',$date);
            $op_time = trim($res[0]);
            $res_op_time = explode(' ',$op_time);

            $open_time  = $res_op_time[0];

            $cl_time = trim($res[1]);
            $res_cl_time = explode(' ',$cl_time);

            $close_time  = $res_cl_time[0];
        }

        return ['date' => $date, 'open_time' => $open_time, 'close_time' => $close_time];
    }
}
