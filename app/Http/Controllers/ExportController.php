<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashMemo;
use App\Exports\CashMemoExport;
use App\Exports\ChallanExport;
use App\Exports\InvoiceExport;
use App\Exports\QouteExport;
use App\Exports\SafetyCareQouteExport;
use App\Exports\ViqasEnterpriseQouteExport;
use App\Exports\GeneralTemplateExport;
use App\Exports\ExpenseExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Qoute;
use App\Models\Customer;
use App\Models\QouteProducts;
use App\Models\SafetyCareQoute;
use App\Models\SafetyCareQouteProducts;
use App\Models\ViqasEnterpriseQoute;
use App\Models\ViqasEnterpriseQouteProducts;
use App\Models\GeneralTemplate;
use App\Models\Branch;
use App\Models\Expense; 

class ExportController extends Controller
{
    public function ExportCashMemo($id)
    {
        return Excel::download(new CashMemoExport($id), 'CashMemo.xlsx');
    }

    public function ChallanExport($id)
    {
        return Excel::download(new ChallanExport($id), 'ChallanExport.xlsx');
    }
    
    public function InvoiceExport($id)
    {
        return Excel::download(new InvoiceExport($id), 'InvoiceExport.xlsx');
    }

    public function SalesreportExport($id)
    {
        return Excel::download(new InvoiceExport($id), 'SalesReport.xlsx');
    }

    public function QouteExport($id)
    {
        $quote          = Qoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts  = QouteProducts::where('quote_id', $id)->get();
        
        if(isset($quoteCustomer)):
            $name = $quoteCustomer->customer_name;
            $city = $quoteCustomer->city;
        else:
            $name = 'na';
            $city = 'na';
        endif;
        
        return Excel::download(new QouteExport($id), $quote->id.'-'.$name.'-'.$city.'.xlsx');
    }


    public function SafetyCareQouteExport($id)
    {
        $quote          = SafetyCareQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts  = SafetyCareQouteProducts::where('quote_id', $id)->get();
        
        return Excel::download(new SafetyCareQouteExport($id), $quote->id.'-'.$quoteCustomer->customer_name.'-'.$quoteCustomer->city.'.xlsx');
    }

    public function ViqasEnterpriseQouteExport($id)
    {
        $quote          = ViqasEnterpriseQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts  = ViqasEnterpriseQouteProducts::where('quote_id', $id)->get();
        
        return Excel::download(new ViqasEnterpriseQouteExport($id), $quote->id.'-'.$quoteCustomer->customer_name.'-'.$quoteCustomer->city.'.xlsx');
    }

    public function GeneralTemplateExport($id)
    {
        $template = GeneralTemplate::find($id);
        return Excel::download(new GeneralTemplateExport($id), $template->name.'.xlsx');
    }

    
    public function exoprtexpense()
    {
        $branches   = Branch::latest()->get();
        $expenses    = Expense::latest()->get();

        return view('Expense.export.index', compact('branches', 'expenses'));
    }

    public function exoprtedexpense(Request $request)
    {
        return Excel::download(new ExpenseExport($request), 'Expense Report.xlsx');
    }
}
