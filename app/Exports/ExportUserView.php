<?php
namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportUserView implements FromView
{
    public $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view(): View
    {
        return view('exports.users', [
            'users' => User::with('city_info')->get()
        ]);
    }
}
