<?php

namespace App\Exports;

use App\Models\CashMemo;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use App\Models\User;
use App\Models\Branch;
use App\Models\Expense; 
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use DateTime;

class ExpenseExport implements
    FromView,
    WithTitle,
    ShouldAutoSize
{
    protected $request;
	function __construct($request) {
		$this->request = $request;
	}
    public function view(): View
    {
        $branch = $this->request->branch;
        $from   = $this->request->from;
        $to     = $this->request->to;
        if (isset($branch)) {
            $branches = Branch::where('id', $branch)->latest()->get();
        }else{
            $branches = Branch::latest()->get();
        }
        
        if (!isset($from)) {
            $from = date('Y-m-d', strtotime(\Carbon\Carbon::now()->subDays(5)));
        }

        if (!isset($to)) {
            $to = date('Y-m-d');
        }

        $begin = new DateTime($from);
        $end   = new DateTime($to);

        return view('exports.expense', [
            'branches'  => $branches,
            'begin'     => $begin,
            'end'       => $end,
        ]);
    }
    public function title(): string 
    {
    	return 'Expense Report';
    }
}