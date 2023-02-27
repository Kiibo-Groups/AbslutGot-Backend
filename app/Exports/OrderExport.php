<?php

namespace App\Exports;

use App\Order;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class OrderExport implements FromView,WithHeadings,WithTitle
{
    public $folder  = "admin/report.";
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $ord = Order::select('name','email','phone','address','d_charges','discount','total')->where('store_id',$_POST['store_id'])->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'date',
            'city'
        ];
    }
    
    public function title(): string
    {
        return 'Reporte de ventas';
    }

    public function view(): view
    {
        $res = new Order;
        $Request = [
            'from' => $_POST['from'],
            'to'   => $_POST['to'],
            'store_id' => $_POST['store_id']
        ];

		return View($this->folder.'report',[

		'data' => $res->getReport($Request),
		'from' => $_POST['from'] ? date('d-M-Y',strtotime($_POST['from'])) : null,
		'to'   => $_POST['to'] ? date('d-M-Y',strtotime($_POST['to'])) : null,
		'user' => new User
		]);
    }
}
