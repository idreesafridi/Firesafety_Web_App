<?php

namespace App\Exports;

use App\Models\CashMemo;
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

class CashMemoExport implements
    FromView,
    WithTitle,
    ShouldAutoSize, 
    WithDrawings,
    WithEvents
{
    protected $id;
	function __construct($id) {
		$this->id = $id;
	}
    public function view(): View
    {
    	$CashMemo = CashMemo::find($this->id);

    	$Descriptions 	= explode('@&%$# ', $CashMemo->descriptions);
      	$Quantity 		= explode('@&%$# ', $CashMemo->qty);
      	$UnitPrice 		= explode('@&%$# ', $CashMemo->unit_price);
        $productCapacity      = explode('@&%$# ', $CashMemo->productCapacity);

      	$count=0; 
      	$count1=1;
        return view('exports.CashMemo', [
            'CashMemo'      => $CashMemo,
            'Descriptions'  => $Descriptions,
            'Quantity'      => $Quantity,
            'UnitPrice'     => $UnitPrice,
            'productCapacity'   => $productCapacity,
        ]);
    }
    public function title(): string 
    {
    	return 'CashMemo';
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
    		}
    	];
    }

    public function drawings()
    {
        $CashMemo = CashMemo::find($this->id);
        $Descriptions   = explode('@&%$# ', $CashMemo->descriptions);
        $count = count($Descriptions)+17;
        
        $user = User::find($CashMemo->user_id);

        $cell1 = $count+2;
        $cell2 = $count+11;

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
        
        if(isset($user)):
            if($user->signature != ''):
                $drawing2 = new Drawing();
                $drawing2->setName('Logo');
                $drawing2->setDescription('This is my logo');
                $drawing2->setPath(public_path('/signature/'.$user->signature));
                $drawing2->setHeight(50);
                $drawing2->setCoordinates('A'.$cell1);
                array_push($drawings, $drawing2);
            endif;
        endif;

        return $drawings;
    }

}