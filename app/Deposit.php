<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';
    protected $fillable =[
        "amount", 
        "customer_id", 
        "user_id", 
        "card_num",
        "bank_name",
        "brand",
        "note"
    ];
 
    function getDeposits($id)
    {
        return Deposit::where('user_id',$id)->orderBy('id','DESC')->get();
    }
}
