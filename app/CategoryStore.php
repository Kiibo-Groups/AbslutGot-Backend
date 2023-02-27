<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
class CategoryStore extends Authenticatable
{
    protected $table = "categorystore";
    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [
            'name'  => 'required',
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
    |Create/Update user
    |--------------------------------
    */

    public function addNew($data,$type)
    {
        $a                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $add                = $type === 'add' ? new CategoryStore : CategoryStore::find($type);
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->status        = isset($data['status']) ? $data['status'] : null;
        $add->type_cat      = isset($data['type_cat']) ? $data['type_cat'] : 0;
        $add->id_cp         = isset($data['id_cp']) ? $data['id_cp'] : 0;
        $add->id_c          = isset($data['id_c']) ? $data['id_c'] : 0;
        $add->sort_no       = isset($data['sort_no']) ? $data['sort_no'] : 0;
        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("upload/categorys/", $filename);   
            $add->img = $filename;   
        }

        $add->s_data        = serialize($a);
        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return CategoryStore::orderBy('id','ASC')->get();
    }

    public function getAllCats()
    {
        $res  = CategoryStore::orderBy('sort_no','ASC')->get();
        $data = [];

        foreach($res as $row)
        {
            $data[] = [
                'id'            => $row->id,
                'name'          => $row->name,
                'img'           => $row->img ? Asset('upload/categorys/'.$row->img) : null,
                'status'        => $row->status,
                'sort_no'       => $row->sort_no,
            ];
        }
        
        return $data;
    }

    public function ViewOrderCats()
    {
        $res  = CategoryStore::where('type_cat',0)->where('status',0)->orderBy('id',"DESC")->get(); // Obtenemos categorias principales
        $data = [];

        foreach($res as $row)
        {

            // Obtenemos SubCategorias
            $subs = [];
            
            $sub_c = CategoryStore::where('type_cat',1)->where('id_cp',$row->id)->where('status',0)->get();
            foreach ($sub_c as $sc) {
                
                // Obtenemos SubSubCategorias
                $subss = [];
                $sub_s = CategoryStore::where('type_cat',2)->where('id_c',$sc->id)->get();
                foreach ($sub_s as $key => $ss) {
                    $subss[] = [
                        'id'            => $ss->id,
                        'name'          => $ss->name,
                        'img'           => $ss->img ? Asset('upload/categorys/'.$ss->img) : null,
                        'status'        => $ss->status,
                    ];
                }

                $subs[] = [
                    'id'            => $sc->id,
                    'name'          => $sc->name,
                    'img'           => $sc->img ? Asset('upload/categorys/'.$sc->img) : null,
                    'status'        => $sc->status,
                    'subcats'       => $subss
                ];
            }

            

            $data[] = [
                'id'            => $row->id,
                'name'          => $row->name,
                'img'           => $row->img ? Asset('upload/categorys/'.$row->img) : null,
                'status'        => $row->status,
                'sort_no'       => $row->sort_no,
                'cats'          => $subs
            ];
        }
        
        return $data;
    }
    

    public function getCatP()
    {
        return CategoryStore::where('type_cat',0)->orderBy('sort_no','ASC')->get();
    }

    public function getCatC()
    {
        return CategoryStore::where('type_cat',1)->orderBy('sort_no','ASC')->get();
    }

    public function getCats()
    {
        return CategoryStore::where('type_cat',2)->orderBy('sort_no','ASC')->get();
    }

    public function getCatID($id)
    {
        return CategoryStore::find($id)->name;
    }
 

    /**
     * Obtener listado de categorias
     * 
     * if id = 7 then > 
     * 8 > Restaurantes
     * 9 > Pizzerias > Restaurantes > Comida
     */
    public function getSelectCat($id)
    {
        $cats = CategoryStore::where('id_cp',$id)->where('type_cat',1)->get();
        $cat_p = CategoryStore::find($id)->name;

        $data = [];
        foreach ($cats as $key) {
            $data[] = [
                'id'            => $key->id,
                'name'          => $key->name,
                'img'           => $key->img ? Asset('upload/categorys/'.$key->img) : null,
                'status'        => $key->status,
                'sort_no'       => $key->sort_no,
            ];
        }

        return $data;
    }

    public function getSelectSubCat($id)
    {
        $cats = CategoryStore::where('id_c',$id)->where('type_cat',0)->get();
        
        $data = [];
        foreach ($cats as $key) {
            $data[] = [
                'id'            => $key->id,
                'name'          => $key->name,
                'img'           => $key->img ? Asset('upload/categorys/'.$key->img) : null,
                'status'        => $key->status,
                'sort_no'       => $key->sort_no,
            ];
        }

        return $data;
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$id]) ? $data[$id] : null;
    }
}
