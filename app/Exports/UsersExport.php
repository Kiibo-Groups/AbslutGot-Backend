<?php

namespace App\Exports;

use App\AppUser;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class UsersExport implements FromView,WithTitle
{
    public $folder  = "admin/report.";
    /**
    * @return \Illuminate\Support\Collection
    */

    public function title(): string
    {
        return 'usuarios_registrados_report';
    }

    public function view(): view
    {
        $res = new AppUser;
        $Request = [
            'user_id' => $_POST['user_id']
        ];

		return View($this->folder.'report_users',[
            'data' => $res->getReport($Request),
            'Users' => new AppUser
		]);
    }
}
