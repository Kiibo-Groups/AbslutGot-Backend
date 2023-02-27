<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
class UserImage extends Authenticatable
{
    protected $table = "user_image";

    public function addNew($data,$id)
    {
        $img = isset($data['gallery']) ? $data['gallery'] : [];

        for($i=0;$i<count($img);$i++)
        {
            $add = new UserImage;
            $add->user_id = $id;
            
            if(isset($img[$i]))
            {
                $filename   = time().rand(111,699).'.' .$img[$i]->getClientOriginalExtension(); 
                $img[$i]->move("upload/user/gallery/", $filename);   
                $add->img = $filename;   
            }

            $add->save();
        }
    }
}
