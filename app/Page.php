<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Page extends Authenticatable
{
    protected $table = "page";

    public function addNew($data)
    {
        $a                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_about_us']) : [];
        $b                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_how']) : [];
        $c                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_faq']) : [];
        $d                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_contact_us']) : [];

        $add                = Page::find(1);
        $add->about_us      = isset($data['about_us']) ? $data['about_us'] : null;
        $add->how           = isset($data['how']) ? $data['how'] : null;
        $add->faq           = isset($data['faq']) ? $data['faq'] : null;
        $add->contact_us    = isset($data['contact_us']) ? $data['contact_us'] : null;
        $add->s_data        = serialize([$a,$b,$c,$d]);

        if(isset($data['about_img']))
        {
            $filename   = time().rand(111,699).'.' .$data['about_img']->getClientOriginalExtension(); 
            $data['about_img']->move("upload/page/", $filename);   
            $add->about_img = $filename;   
        }

        if(isset($data['how_img']))
        {
            $filename   = time().rand(111,699).'.' .$data['how_img']->getClientOriginalExtension(); 
            $data['how_img']->move("upload/page/", $filename);   
            $add->how_img = $filename;   
        }

        $add->save();
    }

    public function getAppData()
    {
        $res = Page::find(1);

        $lid = $_GET['lid'] > 0 ? $_GET['lid'] : 0;

        $about          = $lid > 0 ? $this->getSData($res->s_data,$lid,0) : $res->about_us;
        $how            = $lid > 0 ? $this->getSData($res->s_data,$lid,1) : $res->how;
        $faq            = $lid > 0 ? $this->getSData($res->s_data,$lid,2) : $res->faq;
        $contact_us     = $lid > 0 ? $this->getSData($res->s_data,$lid,3) : $res->contact_us;

        $data = [

        'about_us'        => $about,
        'how'             => $how,
        'faq'             => $faq,
        'contact_us'      => $contact_us,
        'about_img'       => $res->about_img ? Asset('upload/page/'.$res->about_img) : null,
        'how_img'         => $res->how_img ? Asset('upload/page/'.$res->how_img) : null,

        ];

        return $data;
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$field][$id]) ? $data[$field][$id] : null;
    }
}
