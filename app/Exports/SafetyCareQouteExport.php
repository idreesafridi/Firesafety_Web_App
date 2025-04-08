<?php

namespace App\Exports;

use App\Models\SafetyCareQoute;
use App\Models\SafetyCareQouteProducts;
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
 
class SafetyCareQouteExport implements
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
    	$Qoute 			= SafetyCareQoute::find($this->id);
    	$QouteProducts  = SafetyCareQouteProducts::where('quote_id', $this->id)->get();
        $user 			= User::find($Qoute->user_id);
        $quoteCustomer  = Customer::where('id', $Qoute->customer_id)->first();

        return view('exports.SafetyCareQoute', [
            'Qoute'      	=> $Qoute,
            'QouteProducts' => $QouteProducts,
            'user'          => $user,
            'quoteCustomer'	=> $quoteCustomer,
        ]);
    }
    public function title(): string 
    {
    	return 'Safety Care Qoute';
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

                $event->sheet->getStyle('A17:Z30')->applyFromArray([
                    'font' => [
                        'bold'=>true,
                    ],
                ]);
                $event->sheet->getStyle('A36:Z37')->applyFromArray([
                    'font' => [
                        'bold'=>true,
                    ],
                ]);

                $event->sheet->getStyle('A1:Z100')->applyFromArray([
                    'font' => [
                        'font-family'=>'Bodoni Moda',
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
        $Qoute = SafetyCareQoute::find($this->id);

        $user = User::find($Qoute->user_id);

        $count1   = SafetyCareQouteProducts::where('quote_id', $this->id)->count();

        $count = $count1+18;
 
        $cell1 = $count+16;
        $cell2 = $count+24;

        $drawings = [];

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/assets/safety_header.png'));
        $drawing->setHeight(162);
        $drawing->setCoordinates('A1');
        array_push($drawings, $drawing);

        $drawing1 = new Drawing();
        $drawing1->setName('Logo');
        $drawing1->setDescription('This is my logo');
        $drawing1->setPath(public_path('/assets/safety_footer.png'));
        $drawing1->setHeight(217);
        $drawing1->setCoordinates('A'.$cell2);
        array_push($drawings, $drawing1);

        return $drawings;
    }

    public function startCell(): string
    {
        return 'A20';
    }
}
