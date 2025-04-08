<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceProducts;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index(Request $request)
    {
        $from_month_string = (!empty($request->from)) ? $request->from : date('Y-m');
        $from_month = $from_month_string;
        $from_month_string .= "-0";
        $txtFromMonth = date("m", strtotime($from_month_string));
        $txtFromYear = date("Y", strtotime($from_month_string));

        $to_month_string = (!empty($request->to)) ? $request->to : date('Y-m');
        $to_month = $to_month_string;
        $to_month_string .= "-0";
        $txtToMonth = date("m", strtotime($to_month_string));
        $txtToYear = date("Y", strtotime($to_month_string));

        

//        $from_month = date('Y-m');
  //      $to_month = '';
        $year = date('Y');
        $prev_year = date("Y",strtotime("-1 year"));


        // Expired Invoicees
        $categories_ids = Category::select('id')->where('expire_invoice', 'yes')->get();
        $products_ids = Product::select('id')->whereIn('category_id', $categories_ids)->get();

        $invoices_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();
        
        $ExpiredInvoices = Invoice::whereMonth('expiry_date', ">=" ,$txtFromMonth)
                                    ->whereMonth('expiry_date', "<=" ,$txtToMonth)
                                    ->whereYear('expiry_date', ">=" ,$txtFromYear)
                                    ->whereYear('expiry_date', "<=" ,$txtToYear)
                                    //->whereYearBetween('expiry_date', $txtFromYear, $txtToYear)
                                    ->whereIn('id', $invoices_ids)
                                    ->latest()
                                    ->get();
        $categories = Category::latest()->get();
        $totalExpenses = DB::table('expenses')->sum('amount');
        
        
        
        $totalSales = getTotalSales($from_month);
        $invoicesSales = invoicesSales($from_month);
        $cashSales = cashSales($from_month);
        $pendingInvoices = pendingInvoices($from_month);
        $clearInvoices = clearedInvoices($from_month);
        // $totalExpenses = totalExpenses($from_month);
        $noOfInvoices = noOfInvoices($from_month);
        $getNoOfInvoices = getNoOfInvoices($from_month);
        $pendingInvoicescount = pendingInvoicescount($from_month);
        $clearedInvoicecount = clearedInvoiceCount($from_month);
        $invoicesSalescount = invoicesSalescount($from_month);
        $cashSalescount = cashSalescount($from_month);
        $type = 'Current Month';
        
       $products = Product::orderByDesc('inventory')->get();
        
        return view('home', compact('totalSales', 'pendingInvoices', 'totalExpenses', 'noOfInvoices', 'invoicesSales', 'cashSales', 'from_month', 'to_month', 'year', 'ExpiredInvoices', 'categories', 'products','clearInvoices','getNoOfInvoices','pendingInvoicescount','clearedInvoicecount','invoicesSalescount','cashSalescount'));
    }


    public function index_old(Request $request)
    {

        $from_month = date('Y-m');
        $to_month = '';
        $year = date('Y');
        $prev_year = date("Y",strtotime("-1 year"));

        // Expired Invoicees
        $categories_ids = Category::select('id')->where('expire_invoice', 'yes')->get();
        $products_ids = Product::select('id')->whereIn('category_id', $categories_ids)->get();

        $invoices_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();
        
        $ExpiredInvoices =Invoice::whereMonth('expiry_date', $from_month) //where('expiry_date', '<=', date('Y-m-d'))
                                    //->whereYear('expiry_date', $prev_year)
                                    ->whereIn('id', $invoices_ids)
                                    ->latest()
                                    ->get();


        
        $categories = Category::latest()->get();
        
        
        $totalSales = getTotalSales($from_month);
        $invoicesSales = invoicesSales($from_month);
        $cashSales = cashSales($from_month);
        $pendingInvoices = pendingInvoices($from_month);
        $totalExpenses = totalExpenses($from_month);
        //$noOfInvoices = noOfInvoices($from_month);
        $type = 'Current Month';
        
        $products = Product::latest()->get();
        
        return view('home', compact('totalSales', 'pendingInvoices', 'totalExpenses', 'noOfInvoices', 'invoicesSales', 'cashSales', 'from_month', 'to_month', 'year', 'ExpiredInvoices', 'categories', 'products'));
    }

    public function summary(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

//        $from_month = date('Y-m');
  //      $to_month = '';
        $year = date('Y');
        $prev_year = date("Y",strtotime("-1 year"));


        // Expired Invoicees
        $categories_ids = Category::select('id')->where('expire_invoice', 'yes')->get();
        $products_ids = Product::select('id')->whereIn('category_id', $categories_ids)->get();

        $invoices_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();
        
        $ExpiredInvoices = Invoice::whereIn('id', $invoices_ids)
        ->where('expiry_date', '>', date('Y-m-d', strtotime('-1 month', strtotime($from_month))))
        ->where('expiry_date', '<', date('Y-m-d', strtotime('+0 month', strtotime($to_month))))
        ->latest()
        ->get();                                    
        
        $categories = Category::latest()->get();
        $products = Product::latest()->get();
        
        $totalSales = getTotalSales($from_date, $to_date);
        $invoicesSales = invoicesSales($from_date, $to_date);
        $cashSales = cashSales($from_date, $to_date);
        $pendingInvoices = pendingInvoices($from_date, $to_date);
        $clearInvoices = clearedInvoices($from_date, $to_date);
        $totalExpenses = totalExpenses($from_date, $to_date);
        $noOfInvoices = noOfInvoices($from_date, $to_date);
        $getNoOfInvoices = getNoOfInvoices($from_date, $to_date);
        $pendingInvoicescount = pendingInvoicescount($from_date, $to_date);
        $clearedInvoicecount = clearedInvoiceCount($from_date, $to_date);
        $invoicesSalescount = invoicesSalescount($from_date, $to_date);
        $cashSalescount = cashSalescount($from_date, $to_date);

        $type = 'Current Month';
        $products = Product::orderByDesc('inventory')->get();
        return view('home', compact('totalSales', 'pendingInvoices', 'totalExpenses', 'noOfInvoices', 'invoicesSales', 'cashSales', 'from_month', 'to_month', 'year', 'ExpiredInvoices', 'categories', 'products','clearInvoices','getNoOfInvoices','pendingInvoicescount','clearedInvoicecount','invoicesSalescount','cashSalescount'));
        
    }
     
     
    public function salesByProducts(Request $request)
    {
        $type = ($request->type) ? $request->type : 'All Time';
        return view('Graphs.salesByProduct', compact('type'));
    }
    
    public function SalesByCustomers(Request $request)
    {
        $type = ($request->type) ? $request->type : 'All Time';
        return view('Graphs.salesByCustomers', compact('type'));
    }
    
    public function invoicesByStats(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
    
        $Invoices = getNoOfInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        return view('Invoice.invoicesbystats', compact('Invoices', 'companies'));
    }
    
    public function invoicesByStatshow(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

        $invoicesSales = invoicesSales($from_date, $to_date);
        $Invoices = getNoOfInvoices($from_date, $to_date);
    
        $companies = Customer::distinct()->get(['company_name']);
        return view('Invoice.invoiceshow', compact('Invoices', 'companies','invoicesSales'));
    }
    
    
    public function cashmemoByStats(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));        
        $CashMemos = getNoOfCashMemos($from_date, $to_date);
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('CashMemo.index', compact('CashMemos', 'companies'));
    }
 
    public function cashmemobystatsshow(Request $request)
    {
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));        
        $CashMemos = getNoOfCashMemos($from_date, $to_date);
        $cashSales = cashSales($from_date, $to_date);
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('CashMemo.cashmemobystatsshow', compact('CashMemos', 'companies','cashSales'));
    }
    
    public function pendingInvoices(Request $request)
    {
         $from_month = $request->from;
        $to_month = $request->to;
        
        
        $Invoices = getPendingInvoices($from_month, $to_month);
    
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.pendinginvoice', compact('Invoices', 'companies'));
    }

    public function pendinginvoicesshow(Request $request)
    {
         $from_month = $request->from;
        $to_month = $request->to;

        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));

        $pendingInvoices = pendingInvoices($from_date, $to_date);
        $Invoices = getPendingInvoices($from_month, $to_month);
    
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.pendinginvoiceshow', compact('Invoices', 'companies','pendingInvoices'));
    }
    
    public function clearInvoices(Request $request)
    {
        $from_month = $request->from;
        $to_month = $request->to;
        
        $Invoices = getclearedInvoices($from_month, $to_month);
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.clearinvoice', compact('Invoices', 'companies'));
    }

    public function clearedinvoicesshow(Request $request)
    {
        $from_month = $request->from;
        $to_month = $request->to;
       
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));

        $Invoices = getclearedInvoices($from_month, $to_month);
        $clearInvoices = clearedInvoices($from_date, $to_date);

    
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.clearedinvoicesshow', compact('Invoices', 'companies','clearInvoices'));
    }

    public function allinvoicesshow(Request $request)
    {
        $from_month = $request->from;
        $to_month = $request->to;

        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        $Invoices = Invoice::latest()->get();
        $companies  = Customer::distinct()->get(['company_name']);
        $allinvoicesSalesAmount = allinvoicesSalesAmount();
       
        return view('Invoice.allinvoiceshow', compact('Invoices', 'companies','allinvoicesSalesAmount'));
    }

    
    public function expenses(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;
        
        $Invoices = getExpenses($month, $year);
        
        $branches   = Branch::latest()->get();
        $Expenses = Expense::latest()->get();

        //$startDate = Carbon::now()->startOfWeek();
        //$endDate = Carbon::now()->endOfWeek();

        $startDate = Carbon::now()->addDay(1);
        $endDate = Carbon::now()->subDay(4);

        //Init interval
        $dateInterval = \DateInterval::createFromDateString('1 day');
        //Init Date Period from start date to end date
        //1 day is added to end date since date period ends before end date. See first comment: http://php.net/manual/en/class.dateperiod.php
        // $dateperiod = new \DatePeriod($startDate, $dateInterval, $endDate);
        $dateperiod = new \DatePeriod($endDate, $dateInterval, $startDate);

        return view('Expense.index', compact('Expenses', 'branches', 'dateperiod'));
    }
}