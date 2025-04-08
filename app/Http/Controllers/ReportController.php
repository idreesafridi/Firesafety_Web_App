<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Customer;
use DB;
use App\Models\Qoute;
use App\Models\Supplier;
use App\Models\Product;

class ReportController extends Controller
{

    public function salesReport(Request $request)
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

        return view('Report.salesReport', compact('reports', 'type','from_date','to_date'));
    }
    
    
    public function salesReportShow(Request $request)
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
    
        return view('Report.salesReportShow', compact('reports', 'type','from_date','to_date'));
    }
    


    public function quotationReport(Request $request)
    {
        $from_date   = $request->from_date;
        $to_date     = $request->to_date;
        $name        = $request->name;
        $designation = $request->designation;

        $reports = collect();

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
            $reports = Qoute::where('dated', '>=' , $from_date)->latest()->get();
        endif;

        // to date only
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Qoute::where('dated', '<=', $to_date)->latest()->get();
        endif;

        // name
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Qoute::whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
        endif;

        // designation
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Qoute::whereIn('user_id', $usersIds)->latest()->get();
        endif;
        // name + designation
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Qoute::whereIn('user_id', $usersIds)->WhereIn('customer_id', $customerIDs)->latest()->get();
        endif;

        // from date and to date
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Qoute::where('dated', '>=' , $from_date)->latest()->get();
        endif;

        // from date and name
        if(isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $to_date = date('Y-m-d');
            $reports = Qoute::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
        endif;

        // from date and designation
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Qoute::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;


        // to date and name
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Qoute::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
        endif;

        // to date and designation
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Qoute::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from and to and name
        if(isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Qoute::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
        endif;

        // from and to and designation
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Qoute::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // to and name and designation
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Qoute::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
        endif;

        // none
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Qoute::latest()->get();
        endif;

        // all
        if(isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Qoute::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhereIn('customer_id', $customerIDs)->latest()->get();
        endif;
        return view('Report.quotationReport',  compact('reports'));
    }

    public function supplierReport(Request $request)
    {
        $from_date   = $request->from_date;
        $to_date     = $request->to_date;
        $name        = $request->name;
        $designation = $request->designation;

        $reports = collect();

        if(isset($designation)):
            $usersIds = User::where('designation', $designation)->get('id');
        endif;
        
        // from date only
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Supplier::where('dated', '>=' , $from_date)->latest()->get();
        endif;

        // to date only
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Supplier::where('dated', '<=', $to_date)->latest()->get();
        endif;

        // name
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Supplier::Where('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // designation
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Supplier::whereIn('user_id', $usersIds)->latest()->get();
        endif;
        // name + designation
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Supplier::whereIn('user_id', $usersIds)->Where('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // from date and to date
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Supplier::where('dated', '>=' , $from_date)->latest()->get();
        endif;

        // from date and name
        if(isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $to_date = date('Y-m-d');
            $reports = Supplier::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->Where('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // from date and designation
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Supplier::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;


        // to date and name
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Supplier::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->Where('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // to date and designation
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Supplier::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from and to and name
        if(isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Supplier::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->Where('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // from and to and designation
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Supplier::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // to and name and designation
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Supplier::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->WhereIn('customer_id', $customerIDs)->orWhere('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // none
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Supplier::latest()->get();
        endif;

        // all
        if(isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Supplier::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->Where('name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        return view('Report.supplierReport', compact('reports'));
    }

    public function customerReport(Request $request)
    {
        $from_date   = $request->from_date;
        $to_date     = $request->to_date;
        $name        = $request->name;
        $designation = $request->designation;

        $reports = collect();

        if(isset($designation)):
            $usersIds = User::where('designation', $designation)->get('id');
        endif;
        
        // from date only
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Customer::where('dated', '>=' , $from_date)->latest()->get();
        endif;

        // to date only
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Customer::where('dated', '<=', $to_date)->latest()->get();
        endif;

        // name
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Customer::Where('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // designation
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Customer::whereIn('user_id', $usersIds)->latest()->get();
        endif;
        // name + designation
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Customer::whereIn('user_id', $usersIds)->Where('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // from date and to date
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Customer::where('dated', '>=' , $from_date)->latest()->get();
        endif;

        // from date and name
        if(isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $to_date = date('Y-m-d');
            $reports = Customer::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->Where('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // from date and designation
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Customer::where('dated', '>=' , $from_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;


        // to date and name
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Customer::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->Where('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // to date and designation
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Customer::where('dated', '<=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from and to and name
        if(isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Customer::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->Where('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // from and to and designation
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Customer::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // to and name and designation
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Customer::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->orWhere('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;

        // none
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Customer::latest()->get();
        endif;

        // all
        if(isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Customer::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->Where('customer_name', 'LIKE', '%'.$name.'%')->latest()->get();
        endif;
        return view('Report.customerReport', compact('reports'));
    }

    public function expenseReport(Request $request)
    {
        $from_date   = $request->from_date;
        $to_date     = $request->to_date;
        $name        = $request->name;
        $designation = $request->designation;

        $reports = collect();

        if(isset($designation) OR isset($name)):
            $usersIds = User::where('designation', $designation)->orWhere('username', $name)->get('id');
        endif;
        
        // from date only
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $to_date = date('Y-m-d');
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->latest()->get();
        endif;

        // to date only
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Expense::where('dated', '>=', $to_date)->latest()->get();
        endif;

        // name
        if(!isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Expense::whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // designation
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Expense::whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from date and to date
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->latest()->get();
        endif;

        // from date and name
        if(isset($from_date) AND !isset($to_date) AND isset($name) AND !isset($designation)):
            $to_date = date('Y-m-d');
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from date and designation
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND isset($designation)):
            $to_date = date('Y-m-d');
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;


        // to date and name
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Expense::where('dated', '>=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // to date and designation
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Expense::where('dated', '>=', $to_date)->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from and to and name
        if(isset($from_date) AND isset($to_date) AND isset($name) AND !isset($designation)):
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // from and to and designation
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND isset($designation)):
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // to and name and designation
        if(!isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        // none
        if(!isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Expense::latest()->get();
        endif;

        // all
        if(isset($from_date) AND isset($to_date) AND isset($name) AND isset($designation)):
            $reports = Expense::whereBetween('dated', [$from_date, $to_date])->whereIn('user_id', $usersIds)->latest()->get();
        endif;

        return view('Report.expenseReport', compact('reports'));
    }

    public function expiryReport(Request $request)
    {
        $from_date   = $request->from_date;
        $to_date     = $request->to_date;

        $reports = collect();

        // from date only
        if(isset($from_date) AND !isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Product::where('expiry_date', '>=' , $from_date)->latest()->get();
        endif;

        // to date only
        if(!isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Product::where('expiry_date', '<=', $to_date)->latest()->get();
        endif;

        // from date and to date
        if(isset($from_date) AND isset($to_date) AND !isset($name) AND !isset($designation)):
            $reports = Product::where('expiry_date', '>=' , $from_date)->latest()->get();
        endif;
 
        return view('Report.expiryReport', compact('reports'));
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