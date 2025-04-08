<?php

namespace App\Exports;

use App\Models\Challan;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
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

class ChallanExport implements 
    FromView,
    WithTitle,
    ShouldAutoSize, 
    WithDrawings,
    WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;
	function __construct($id) {
		$this->id = $id;
	}
    public function view(): View
    {
    	$Challan = Challan::find($this->id);

        return view('exports.ChallanExport', [
            'Challan'      => $Challan
        ]);
    }
    public function title(): string 
    {
    	return 'ChallanExport';
    }
    public function registerEvents(): array 
    {
    	return [
    		AfterSheet::class => function(AfterSheet $event){
    			$event->sheet->getStyle('A9:Z9')->applyFromArray([
    				'font' => [
    					'bold'=>true,
    					'size'=>12,
    				]
    			]);
    			$event->sheet->getStyle('A11:A13')->applyFromArray([
    				'font' => [
    					'bold'=>true,
    					'size'=>12,
    				]
    			]);
    			$event->sheet->getStyle('C12:C13')->applyFromArray([
    				'font' => [
    					'bold'=>true,
                        'size'=>12,
    				],
     			]);
                $event->sheet->getStyle('A16:Z16')->applyFromArray([
                    'font' => [
                        'bold'=>true,
                        'size'=>12,
                    ],
                ]);
    		}
    	];
    }

    public function drawings()
    {
        $Challan = Challan::find($this->id);
        $Descriptions   = explode('@&%$# ', $Challan->descriptions);
        $count = count($Descriptions)+15;

        $cell1 = $count+10;

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
        $drawing1->setCoordinates('A'.$cell1);
        array_push($drawings, $drawing1);
        
        return $drawings;
    }
}
