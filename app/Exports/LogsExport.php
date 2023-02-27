<?php

namespace App\Exports;

use App\Logs;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class LogsExport implements FromView,WithTitle
{
    public $folder  = "admin/logs.";
    /**
    * @return \Illuminate\Support\Collection
    */
   
    
    public function title(): string
    {
        return 'Archivo de Logs';
    }

    public function view(): view
    {
        $res = new Logs;
        $Request = [
            'from' => $_POST['from'],
            'to'   => $_POST['to'],
            'store' => isset($_POST['store']) ? $_POST['store'] : 0
        ];

		return View($this->folder.'logs',[

		'data' => $res->getReport($Request),
		'from' => $_POST['from'] ? date('d-M-Y',strtotime($_POST['from'])) : null,
		'to'   => $_POST['to'] ? date('d-M-Y',strtotime($_POST['to'])) : null,
		'user' => new User
		]);
    }
}
