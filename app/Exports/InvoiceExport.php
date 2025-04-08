<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\InvoiceProducts;
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

class InvoiceExport implements 
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
    	$Invoice = Invoice::find($this->id);
    	$InvoiceProducts = InvoiceProducts::where('invoice_id', $this->id)->get();
    	$user = User::find($Invoice->user_id);
    	
        return view('exports.Invoice', [
            'Invoice'      	  => $Invoice,
            'InvoiceProducts' => $InvoiceProducts,
            'user'  => $user,
        ]);
    }
    public function title(): string 
    {
    	return 'Invoice';
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
    			$event->sheet->getStyle('A19:Z19')->applyFromArray([
    				'font' => [
    					'bold'=>true,
    					'size'=>12,
    				]
    			]);
    			$event->sheet->getStyle('E23:F26')->applyFromArray([
    				'font' => [
    					'bold'=>true,
    					'size'=>12,
    				]
    			]);
    		}
    	];
    }

    public function drawings()
    {
        $Invoice = Invoice::find($this->id);
        $count1 = InvoiceProducts::where('invoice_id', $this->id)->count();
        
        $user = User::find($Invoice->user_id);

        $count = $count1+18;

        $cell1 = $count+3;
        $cell2 = $count+18;

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

        $drawing2 = new Drawing();
        $drawing2->setName('Logo');
        $drawing2->setDescription('This is my logo');
        $drawing2->setPath(public_path('/signature/'.$user->signature));
        $drawing2->setHeight(80);
        $drawing2->setCoordinates('A'.$cell1);
        array_push($drawings, $drawing2);

        return $drawings;
    }
}
