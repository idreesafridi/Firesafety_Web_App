<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Salary;
use Auth;
use App\Models\Employee;
use PDF;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceProducts;
use App\Models\Supplier;
use App\Models\QouteProducts;
use App\Models\Qoute;
use App\Models\Challan;
use App\Models\CashMemo;
use App\Models\CashmemoProduct;

class DownloadController extends Controller
{
    public function downloadPayrol($id)
    {
        $Salary  = Salary::find($id);
        $staff = Employee::find($Salary->user_id);
        
        $data = ['Salary' => $Salary, 'staff' => $staff, ];
        
        return view('Download.payrol', compact('Salary', 'staff'));
        
        // $pdf = PDF::loadView('Download.payrol', $data);

        // return $pdf->download('Payroll.pdf');
    }

    public function downloadPayrol1($id)
    {
        $Salary  = Salary::find($id);
        $staff = Employee::find($Salary->user_id);
        
        $data = ['Salary' => $Salary, 'staff' => $staff, ];
        
        return view('Download.payrol1', compact('Salary', 'staff'));
        
        // $pdf = PDF::loadView('Download.payrol', $data);

        // return $pdf->download('Payroll.pdf');
    }
    
    public function downloadInvoice($id)
    {
        $Invoice  = Invoice::find($id);

        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $id)->orderBy('sequence', 'asc')->get();
        $InvoiceSupplier   = Supplier::where('id', $Invoice->supplier_id)->first();
        
        $user = User::find($Invoice->user_id);
        
        return view('Download.Invoice', compact('Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'InvoiceSupplier', 'user'));
        
        // $data = ['Invoice' => $Invoice, 'InvoiceCustomer' => $InvoiceCustomer, 'InvoiceProducts' => $InvoiceProducts, 'InvoiceSupplier' => $InvoiceSupplier];
        // $pdf = PDF::loadView('Download.Invoice', $data);
  
        // return $pdf->download('Invoice.pdf');
    }
    
    public function downloadInvoice2($id)
    {
        $Invoice  = Invoice::find($id);

        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $id)->orderBy('sequence', 'asc')->get();
        $InvoiceSupplier   = Supplier::where('id', $Invoice->supplier_id)->first();
        
        $user = User::find($Invoice->user_id);
        
        return view('Download.Invoice2', compact('Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'InvoiceSupplier', 'user'));
        
        // $data = ['Invoice' => $Invoice, 'InvoiceCustomer' => $InvoiceCustomer, 'InvoiceProducts' => $InvoiceProducts, 'InvoiceSupplier' => $InvoiceSupplier];
        // $pdf = PDF::loadView('Download.Invoice', $data);
  
        // return $pdf->download('Invoice.pdf');
    }
    
    public function downloadclearedinvoiceshow1(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getclearedInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        $clearInvoices = clearedInvoices($from_date, $to_date);

        return view('Download.clearedinvoiceshow1', compact('Invoices', 'companies','invoicesSales','clearInvoices')); 
        // $data = ['Invoice' => $Invoice, 'InvoiceCustomer' => $InvoiceCustomer, 'InvoiceProducts' => $InvoiceProducts, 'InvoiceSupplier' => $InvoiceSupplier];
        // $pdf = PDF::loadView('Download.Invoice', $data);
  
        // return $pdf->download('Invoice.pdf');
    }
    
    public function downloadclearedinvoiceshow(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getclearedInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        $clearInvoices = clearedInvoices($from_date, $to_date);

        return view('Download.clearedinvoiceshow', compact('Invoices', 'companies','invoicesSales','clearInvoices')); 

    }
    
        public function downloadpendinginvoiceshow(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getpendingInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        $pendingInvoices = pendingInvoices($from_date, $to_date);

        return view('Download.pendinginvoiceshow', compact('Invoices', 'companies','invoicesSales','pendingInvoices')); 

    }

    public function downloadpendinginvoiceshow1(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        
        
        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getpendingInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        $pendingInvoices = pendingInvoices($from_date, $to_date);

        return view('Download.pendinginvoiceshow1', compact('Invoices', 'companies','invoicesSales','pendingInvoices')); 
    }
    
        public function downloadcashsaleshow1(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));        
        $CashMemos = getNoOfCashMemos($from_date, $to_date);
        $cashSales = cashSales($from_date, $to_date);
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Download.cashsalesshow1', compact('CashMemos', 'companies','cashSales'));
    }

    public function downloadcashsaleshow(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));        
        $CashMemos = getNoOfCashMemos($from_date, $to_date);
        $cashSales = cashSales($from_date, $to_date);
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Download.cashsalesshow', compact('CashMemos', 'companies','cashSales'));
    }
    
        public function downloadallinvoiceshow1(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getNoOfInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        return view('Download.allinvoiceshow1', compact('Invoices', 'companies','invoicesSales'));
    }
    
    public function downloadallinvoiceshow(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getNoOfInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        return view('Download.allinvoiceshow', compact('Invoices', 'companies','invoicesSales'));
    }
    
       public function downloadallinvoice1(Request $request)
    {
        $from_month = $request->from;
        $to_month = $request->to;

        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        $Invoices = Invoice::latest()->get();
        $companies  = Customer::distinct()->get(['company_name']);
        $allinvoicesSalesAmount = allinvoicesSalesAmount();
        return view('Download.allinvoice1', compact('Invoices', 'companies','allinvoicesSalesAmount'));
    }

    public function downloadallinvoice(Request $request)
    {
        $from_month = $request->from;
        $to_month = $request->to;

        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        $Invoices = Invoice::latest()->get();
        $companies  = Customer::distinct()->get(['company_name']);
        $allinvoicesSalesAmount = allinvoicesSalesAmount();
        return view('Download.allinvoice', compact('Invoices', 'companies','allinvoicesSalesAmount'));
    }

    
    public function downloadSalesInvoice($id)
    {
        $Invoice  = Invoice::find($id);

        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $id)->orderBy('sequence', 'asc')->get();
        $InvoiceSupplier   = Supplier::where('id', $Invoice->supplier_id)->first();
        
        $user = User::find($Invoice->user_id);
        
        return view('Download.SalesTaxInvoice', compact('Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'InvoiceSupplier', 'user'));
        
        // $data = ['Invoice' => $Invoice, 'InvoiceCustomer' => $InvoiceCustomer, 'InvoiceProducts' => $InvoiceProducts, 'InvoiceSupplier' => $InvoiceSupplier];
        // $pdf = PDF::loadView('Download.SalesTaxInvoice', $data);
  
        // return $pdf->download('SalesTaxInvoice.pdf');
    }
    public function downloadSalesInvoice2($id)
    {
        $Invoice  = Invoice::find($id);

        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $id)->orderBy('sequence', 'asc')->get();
        $InvoiceSupplier   = Supplier::where('id', $Invoice->supplier_id)->first();
        
        $user = User::find($Invoice->user_id);
        
        return view('Download.SalesTaxInvoice2', compact('Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'InvoiceSupplier', 'user'));
        
        // $data = ['Invoice' => $Invoice, 'InvoiceCustomer' => $InvoiceCustomer, 'InvoiceProducts' => $InvoiceProducts, 'InvoiceSupplier' => $InvoiceSupplier];
        // $pdf = PDF::loadView('Download.SalesTaxInvoice', $data);
  
        // return $pdf->download('SalesTaxInvoice.pdf');
    }
    
    public function downloadQuoteOne($id)
    {
        $quote  = Qoute::find($id);
        if(isset($quote->customer_id)):
            $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        else:
            $quoteCustomer  = null;
        endif;
        $QouteProducts   = QouteProducts::where('quote_id', $id)->orderBy('sequence', 'asc')->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();

        $user = User::find($quote->user_id);
        
        return view('Download.TemplateOne', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier', 'user'));
    }
    public function downloadQuote2($id)
    {
        $quote  = Qoute::find($id);
        if(isset($quote->customer_id)):
            $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        else:
            $quoteCustomer  = null;
        endif;
        
        $QouteProducts   = QouteProducts::where('quote_id', $id)->orderBy('sequence', 'asc')->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();

        $user = User::find($quote->user_id);
        
        return view('Download.Template2', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier', 'user'));
    }

    public function downloadQuoteOnewithoutimage($id)
    {
        $quote  = Qoute::find($id);
        if(isset($quote->customer_id)):
            $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        else:
            $quoteCustomer  = null;
        endif;
        $QouteProducts   = QouteProducts::where('quote_id', $id)->orderBy('sequence', 'asc')->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();

        $user = User::find($quote->user_id);
        
        return view('Download.TemplateOnewithoutimage', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier', 'user'));
    }
    public function downloadQuoteTwowithoutimage($id)
    {
        $quote  = Qoute::find($id);
        if(isset($quote->customer_id)):
            $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        else:
            $quoteCustomer  = null;
        endif;
        
        $QouteProducts   = QouteProducts::where('quote_id', $id)->orderBy('sequence', 'asc')->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();

        $user = User::find($quote->user_id);
        
        return view('Download.Template2withoutimage', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier', 'user'));
    }
    
    public function downloadQuoteTwo($id)
    {
        $quote  = Qoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        
        return view('Download.TemplateTwo', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier'));
        
        // $data = ['quote' => $quote, 'quoteCustomer' => $quoteCustomer, 'QouteProducts' => $QouteProducts, 'QouteSupplier' => $QouteSupplier];
        // $pdf = PDF::loadView('Download.TemplateTwo', $data);
  
        // return $pdf->download('Quote.pdf');
    }
    
    public function downloadQuoteThree($id)
    {
        $quote  = Qoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
            
        return view('Download.TemplateThree', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier'));
        
        // $data = ['quote' => $quote, 'quoteCustomer' => $quoteCustomer, 'QouteProducts' => $QouteProducts, 'QouteSupplier' => $QouteSupplier];
        // $pdf = PDF::loadView('Download.TemplateThree', $data);
  
        // return $pdf->download('Quote.pdf');
    }
    
    public function downloadQuoteFour($id)
    {
        $quote  = Qoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        $QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        
        return view('Download.TemplateFour', compact('quote', 'quoteCustomer', 'QouteProducts', 'QouteSupplier'));
        
        // $data = ['quote' => $quote, 'quoteCustomer' => $quoteCustomer, 'QouteProducts' => $QouteProducts, 'QouteSupplier' => $QouteSupplier];
        // $pdf = PDF::loadView('Download.TemplateFour', $data);
  
        // return $pdf->download('Quote.pdf');
    }

    public function IncommingChallanDownload($id)
    {
        $Challan = Challan::find($id);
        
        $user = User::find($Challan->user_id);
        
        return view('Download.IncommingChallan', compact('Challan', 'user'));
    }
    public function IncommingChallanDownload2($id)
    {
        $Challan = Challan::find($id);
        
        $user = User::find($Challan->user_id);
        
        return view('Download.IncommingChallan2', compact('Challan', 'user'));
    }

    public function DeliveryChallanDownload($id)
    {
        $Challan = Challan::find($id);
        $user = User::find($Challan->user_id);
        
        return view('Download.DeliveryChallan', compact('Challan', 'user'));
    }
    
    public function DeliveryChallanDownload2($id)
    {
        $Challan = Challan::find($id);
        $user = User::find($Challan->user_id);
        
        return view('Download.DeliveryChallan2', compact('Challan', 'user'));
    }

    public function CashMemoDownload($id)
    {
        $CashMemo = CashMemo::find($id);
        $user = User::find($CashMemo->user_id);
        $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $id)->orderBy('sequence', 'asc')->get();
        
        return view('Download.CashMemoDownload', compact('CashMemo', 'user', 'CashmemoProducts'));
    }
    
    public function CashMemoDownload2($id)
    {
        $CashMemo = CashMemo::find($id);
        $user = User::find($CashMemo->user_id);
        $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $id)->orderBy('sequence', 'asc')->get();
        
        return view('Download.CashMemoDownload2', compact('CashMemo', 'user', 'CashmemoProducts'));
    }

    public function downloadsalesreport(Request $request)
    {
 $from_date   = $request->from_date;
        $to_date     = $request->to_date;
        $name        = $request->name;
        $designation = $request->designation;
        
        $type = $request->type;
 
        $reports = collect();
        
        if ($type === 'clear') {
            $reports = Invoice::where('payment_status', 'Cleared')->latest()->get();
            if ($from_date && $to_date) {
                $reports = $reports->whereBetween('dated', [$from_date, $to_date]);
            }
        
        } elseif ($type === 'clear1') {
            $reports = Invoice::where('payment_status', 'Pending')->latest();
        
            if ($from_date && $to_date) {
                $reports = $reports->whereBetween('dated', [$from_date, $to_date]);
            }
        
            $reports = $reports->get();
        }
         else {
            if(isset($designation) OR isset($name)):
                if(!empty($name)):
                    $customerIDs = Customer::where('customer_name',  'LIKE', '%' . $name . '%')->get('id');
                    $usersIds = User::where('designation', $designation)->orWhere('username', 'LIKE' , '%' . $name . '%')->get('id');
                else:
                    $usersIds = User::where('designation', $designation)->get('id');
                endif;
            endif;
            
            // from date only
            if(isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '>=' , $from_date)->latest()->get();
            endif;
    
            // to date only
            if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '<=', $to_date)->latest()->get();
            endif;
    
            // name
            if(!isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
                $reports = Invoice::whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // designation
            if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::whereIn('user_id', $usersIds)->latest()->get();
            endif;
            // name + designation
            if(!isset($from_date) AND !isset($to_date) AND isset($name) AND isset($designation)):
                $reports = Invoice::whereIn('user_id', $usersIds)->WhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // from date and to date
            if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '>=' , $from_date)->latest()->get();
            endif;
    
            // from date and name
            if(isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
                $to_date = date('Y-m-d');
                $reports = Invoice::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // from date and designation
            if(isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->latest()->get();
            endif;
    
    
            // to date and name
            if(!isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // to date and designation
            if(!isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
            endif;
    
            // from and to and name
            if(isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // from and to and designation
            if(isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
            endif;
    
            // to and name and designation
            if(!isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // none
            if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::latest()->get();
            endif;
     
            // all
            if(isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
        }
            $data = request()->input('data_key');
    
        
            return view('Download.salesreport', compact('reports','type'));
        }
    
    public function downloadsalesreport1(Request $request)

    {
 $from_date   = $request->from_date;
        $to_date     = $request->to_date;
        $name        = $request->name;
        $designation = $request->designation;
        
        $type = $request->type;
 
        $reports = collect();
        
        if ($type === 'clear') {
            $reports = Invoice::where('payment_status', 'Cleared')->latest()->get();
            if ($from_date && $to_date) {
                $reports = $reports->whereBetween('dated', [$from_date, $to_date]);
            }
        
        } elseif ($type === 'clear1') {
            $reports = Invoice::where('payment_status', 'Pending')->latest();
        
            if ($from_date && $to_date) {
                $reports = $reports->whereBetween('dated', [$from_date, $to_date]);
            }
        
            $reports = $reports->get();
        }
         else {
            if(isset($designation) OR isset($name)):
                if(!empty($name)):
                    $customerIDs = Customer::where('customer_name',  'LIKE', '%' . $name . '%')->get('id');
                    $usersIds = User::where('designation', $designation)->orWhere('username', 'LIKE' , '%' . $name . '%')->get('id');
                else:
                    $usersIds = User::where('designation', $designation)->get('id');
                endif;
            endif;
            
            // from date only
            if(isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '>=' , $from_date)->latest()->get();
            endif;
    
            // to date only
            if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '<=', $to_date)->latest()->get();
            endif;
    
            // name
            if(!isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
                $reports = Invoice::whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // designation
            if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::whereIn('user_id', $usersIds)->latest()->get();
            endif;
            // name + designation
            if(!isset($from_date) AND !isset($to_date) AND isset($name) AND isset($designation)):
                $reports = Invoice::whereIn('user_id', $usersIds)->WhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // from date and to date
            if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '>=' , $from_date)->latest()->get();
            endif;
    
            // from date and name
            if(isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
                $to_date = date('Y-m-d');
                $reports = Invoice::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // from date and designation
            if(isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->latest()->get();
            endif;
    
    
            // to date and name
            if(!isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
                $reports = Invoice::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // to date and designation
            if(!isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
            endif;
    
            // from and to and name
            if(isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // from and to and designation
            if(isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
            endif;
    
            // to and name and designation
            if(!isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
    
            // none
            if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
                $reports = Invoice::latest()->get();
            endif;
     
            // all
            if(isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
                $reports = Invoice::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
            endif;
            // Your existing logic for generating reports...
        }
        $data = request()->input('data_key');

    
        return view('Download.salesreport1', compact('reports','type'));
       }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}