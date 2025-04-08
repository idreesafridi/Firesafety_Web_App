<?php

namespace App\Exports;

use App\Models\Qoute;
use App\Models\QouteProducts;
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

class QouteExport implements
    FromView,
    WithTitle,
    ShouldAutoSize, 
    WithEvents,
    WithDrawings,
    WithCustomStartCell
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
    	$Qoute = Qoute::find($this->id);
    	$QouteProducts   = QouteProducts::where('quote_id', $this->id)->get();
        $user = User::find($Qoute->user_id);

        return view('exports.Qoute', [
            'Qoute'      	=> $Qoute,
            'QouteProducts' => $QouteProducts,
            'user'          => $user,
        ]);
    }
    public function title(): string 
    {
    	return 'Qoute';
    }
    public function registerEvents(): array 
    {
    	return [
    		AfterSheet::class => function(AfterSheet $event){
    			$event->sheet->getStyle('A1:A6')->applyFromArray([
    				'font' => [
    					'bold'=>true,
    					'size'=>12,
    				]
    			]);
    			$event->sheet->getStyle('A8:D8')->applyFromArray([
    				'font' => [
    					'bold'=>true,
    					'size'=>12,
    				]
    			]);
    			$event->sheet->getStyle('A1:U100')->applyFromArray([
    				'font' => [
    					'size'=>12,
    				],
     			]);
                $event->sheet->getStyle('A9:D9')->applyFromArray([
                    'font' => [
                        'bold'=>true,
                        'size'=>12,
                    ],
                ]);

                $event->sheet->getStyle('A16:Z16')->applyFromArray([
                    'font' => [
                        'bold'=>true,
                    ],
                ]);
                $event->sheet->getStyle('A38:Z38')->applyFromArray([
                    'font' => [
                        'bold'=>true,
                    ],
                ]);

                $event->sheet->getStyle('A11:D14')->applyFromArray([
                    'allBorders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color'       => ['argb' => '000000'],
                            ],
                        ]
                ]);
    		}
    	];
    }

    public function drawings()
    {
        $Qoute = Qoute::find($this->id);

        $user = User::find($Qoute->user_id);

        $count1   = QouteProducts::where('quote_id', $this->id)->count();

        $count = $count1+18;
 
        $cell1 = $count+16;
        $cell2 = $count+24;

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
        $drawing1->setCoordinates('A'.$cell2);
        array_push($drawings, $drawing1);


        if($user->signature != ''):
            $drawing1 = new Drawing();
            $drawing1->setName('Logo');
            $drawing1->setDescription('This is my logo');
            $drawing1->setPath(public_path('/signature/'.$user->signature));
            $drawing1->setHeight(80);
            $drawing1->setCoordinates('A'.$cell1);
            array_push($drawings, $drawing1);
        endif;

        return $drawings;
    }

    public function startCell(): string
    {
        return 'A20';
    }
}
