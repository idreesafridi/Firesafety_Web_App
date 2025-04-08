<?php

namespace App\Exports;

use App\Models\GeneralTemplate;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Customer;

class GeneralTemplateExport implements 
    FromView,
    WithTitle,
    ShouldAutoSize, 
    WithEvents,
    WithDrawings,
    WithCustomStartCell
{
    protected $id;
	function __construct($id) {
		$this->id = $id;
	}

    public function view(): View
    { 
    	$template 			= GeneralTemplate::find($this->id);

        return view('exports.GeneralTemplate', [
            'template'      	=> $template,
        ]);
    }

    public function title(): string 
    {
    	$template  = GeneralTemplate::find($this->id);
    	return $template->name;
    }

    public function registerEvents(): array 
    {
    	return [
    		AfterSheet::class => function(AfterSheet $event){
    			$event->sheet->getStyle('A1:Z1000')->applyFromArray([
    				'font' => [
    					'size'=>12,
    				]
    			]);
    		}
    	];
    }

    public function drawings()
    {
        $drawings = [];

       $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/assets/header-excel.jpg'));
        $drawing->setHeight(162);
        $drawing->setCoordinates('A1');
        array_push($drawings, $drawing);

        $drawing1 = new Drawing();
        $drawing1->setName('Logo');
        $drawing1->setDescription('This is my logo');
        $drawing1->setPath(public_path('/assets/footer-excel.jpg'));
        $drawing1->setHeight(217);
        $drawing1->setCoordinates('A100');
        array_push($drawings, $drawing1);

        return $drawings;
    }

    public function startCell(): string
    {
        return 'A20';
    }
}
