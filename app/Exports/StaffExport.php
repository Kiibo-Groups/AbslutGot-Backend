<?php

namespace App\Exports;

use App\Delivery;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class StaffExport implements FromView,WithHeadings,WithTitle
{
    public $folder  = "admin/report.";
    /**
    * @return \Illuminate\Support\Collection
    */

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
        return 'socios_repartidores_report';
    }

    public function view(): view
    {
        $res = new Delivery;
        $Request = [
            'staff_id' => $_POST['staff_id']
        ];

		return View($this->folder.'report_staff',[
            'data' => $res->getReport($Request),
            'staff' => new Delivery
		]);
    }
}
