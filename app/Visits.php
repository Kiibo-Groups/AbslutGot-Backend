<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DateTime;
class Visits extends Authenticatable
{
   protected $table = 'visits_store';

   public function addNew($store_id,$user_id)
   { 
        // Comprobamos si existe
        $visits = Visits::where('user_id',$user_id)->where('store_id',$store_id)->first();
        if (isset($visits->id)) { // Ya tenia una visita contada 
            $query_time = new DateTime(date("Y-M-d H:i")); // Tiempo de hoy
            $visit_time = new DateTime($visits->updated_at);
            $v_count    = Admin::find(1)->v_count; // Tipo de conteo (Horas, Dias, Semanas, Meses)
            $v_value    = Admin::find(1)->v_value; // Valor del conteo
            $add_flag   = false;
            // Obtenemos la diferencia de tiempo
            $diff = $visit_time->diff($query_time);

            if ($v_count == 0) { // Horas
                $horas_transcurridas = (24*$diff->d)+$diff->h;
                if ($horas_transcurridas >= $v_value) {
                    // Agregamos visita
                    $add_flag = true;
                    $visit_count = $visits->visits +1;
                    $this->AddVisit($store_id,$user_id,$visits->id,$visit_count);
                }
            }elseif ($v_count == 1) { // Dias
                $dias_transcurridos = (30*$diff->m)+$diff->d;
                if ($dias_transcurridos >= $v_value) {
                    // Agregamos visita
                    $add_flag = true;
                    $visit_count = $visits->visits +1;
                    $this->AddVisit($store_id,$user_id,$visits->id,$visit_count);
                }
            }elseif ($v_count == 2) { // Semanas
                $semanas_transcurridas = (4*$diff->m)+$diff->m;
                if ($semanas_transcurridas >= $v_value) {
                    // Agregamos visita
                    $add_flag = true;
                    $visit_count = $visits->visits +1;
                    $this->AddVisit($store_id,$user_id,$visits->id,$visit_count);
                }
            }elseif ($v_count == 3) { // Meses
                $meses_transcurridos = (12*$diff->y)+$diff->m;
                if ($meses_transcurridos >= $v_value) {
                    // Agregamos visita
                    $add_flag = true;
                    $visit_count = $visits->visits +1;
                    $this->AddVisit($store_id,$user_id,$visits->id,$visit_count);
                }
            }
            
            return [
                'done' => true
            ];
        }else { // Registramos de nuevo
            $add = new Visits;
            $add->user_id   = $user_id;
            $add->store_id  = $store_id;
            $add->visits    = 1;
            $add->save();

            return [
                'done' => true
            ];
        }
   }

   public function AddVisit($store_id,$user_id,$visit_id,$visit_count)
   {
        $add            = Visits::find($visit_id);
        $add->user_id   = $user_id;
        $add->store_id  = $store_id;
        $add->visits    = $visit_count;
        $add->save();

        return [
            'done' => true
        ];
   }

}
