<?php

namespace App\Exports;

use App\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::select('category_id','name','description','small_price','medium_price','large_price','sort_no')->where('store_id',Auth::user()->id)->get();
    }

    public function headings(): array
    {
        return [
            'Category ID',
            'Name',
            'Description',
            'Small Price',
            'Medium Price',
            'Large Price',
            'Sort No',
        ];
    }
}
