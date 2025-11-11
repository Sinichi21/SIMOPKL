<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ComplaintsExport implements FromView
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function view(): View
    {
        $complaints = Complaint::whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->get();

        return view('admin.complaint.reportExcel', [
            'complaints' => $complaints
        ]);
    }
}
