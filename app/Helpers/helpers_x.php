<?php
use Carbon\Carbon;
use App\Models\User;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProducts;
use App\Models\CashMemo;
use App\Models\CashmemoProduct;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;

if (! function_exists('expensesofweek')) {
    function expensesofweek($branch, $date)
    {
        // $Expenses = Expense::select("*")
       	// 			->where('branch_id', $branch)
        //         	->whereBetween('created_at', 
        //                 [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
        //             )
        //             ->latest()
        //         	->get();

       	$Expenses = Expense::select("*")
       				->where('branch_id', $branch)
       				->where('dated', $date)
                    ->latest()
                	->get();
        return $Expenses;
    }
}

if (! function_exists('getInvoicePayment')) {
    function getInvoicePayment($branch, $date)
    { 
    	$user_ids = User::select('id')->where('branch_id', $branch)->get();
    	$invoices_ids = Invoice::select('id')->whereIn('user_id', $user_ids)->get();
       	$paymented = InvoicePayment::select("*")
       				->whereIn('invoice_id', $invoices_ids)
       				->where('dated', $date)
                	->sum('amount_recieved');
        return $paymented;
    }
}

if (! function_exists('getTotalSales')) {
    function getTotalSales($month = '', $year)
    {
        switch ($month) {
            case '1':
                $invoicesSales = invoicesSales(1, $year);
                $cashSales = cashSales(1, $year);
                break;
            case '3':
                $invoicesSales = invoicesSales(3, $year);
                $cashSales = cashSales(3, $year);
                break;
            case '6':
                $invoicesSales = invoicesSales(6, $year);
                $cashSales = cashSales(6, $year);
                break;
            case '12':
                $invoicesSales = invoicesSales(12, $year);
                $cashSales = cashSales(12, $year);
                break;
            default:
                $invoicesSales = invoicesSales(6, $year);
                $cashSales = cashSales(6, $year);
                break;
        }
        
        return $invoicesSales+$cashSales;
    }
}

if (! function_exists('cashSales')) {
    function cashSales($month = '', $year)
    {
        switch ($month) {
            case '1':
                // $CashMemos = CashMemo::whereBetween('dated', [Carbon::now()->subMonth(1), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                $CashMemos = CashMemo::whereMonth('dated', 1)->whereYear('dated', $year)->latest()->get();
                break;
            case '3': // Jan, Feb, March
                $newMonth = new Carbon('last day of March '.$year);
                //$CashMemos = CashMemo::whereBetween('dated', [Carbon::now()->subMonth(3), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                $CashMemos = CashMemo::whereBetween('dated', [$newMonth->subMonth(3), $newMonth])->whereYear('dated', $year)->latest()->get();
                break;
            case '6':
                $CashMemos = CashMemo::whereBetween('dated', [Carbon::now()->subMonth(6), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                break;
            case '12':
                $CashMemos = CashMemo::whereBetween('dated', [Carbon::now()->subMonth(12), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                break;
            default:
                $CashMemos = CashMemo::latest()->get();
                break;
        }
        
        $netTotal = 0;
        if($CashMemos->count() > 0):
            foreach($CashMemos as $CashMemo):
                $totalPrice=0;
                $CashmemoProducts   = App\Models\CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
                if($CashmemoProducts):
                    foreach($CashmemoProducts as $Product):
                        $subtotal   = $Product->qty*$Product->unit_price;
                        $totalPrice += $subtotal;
                    endforeach;
                endif;

                $Descriptions   = explode('@&%$# ', $CashMemo->descriptions);
                $Quantity       = explode('@&%$# ', $CashMemo->qty);
                $UnitPrice      = explode('@&%$# ', $CashMemo->unit_price);
                $productCapacity      = explode('@&%$# ', $CashMemo->productCapacity);
                $total = $totalPrice;

                if(!empty($Descriptions)):
                $count1=0;
                foreach($Descriptions as $Description):
                if(!empty($Description)):
                    $qty    = (float)$Quantity[$count1];
                    $price  = (float)$UnitPrice[$count1];
                    
                    $sub = $price;

                    $net = $qty*$sub;
                    $total += $net;
                endif;
                endforeach;
                endif;

                $new_total_price=$total; 
                $transportaion = 0;

                if($CashMemo->discount_percent > 0 || $CashMemo->discount_fixed > 0):
                if(isset($CashMemo->discount_percent)):
                    $discount_value     = ($total / 100) * $CashMemo->discount_percent;
                    $new_total_price    = $total - $discount_value;
                elseif(isset($CashMemo->discount_fixed)):
                    $discount_value = $CashMemo->discount_fixed;
                    $new_total_price    = $total - $discount_value;
                endif;
               
                if (isset($CashMemo->transportaion) && $CashMemo->transportaion > 0):
                    $transportaion = $CashMemo->transportaion;
                endif;
                endif;
                $grand_total = $new_total_price+$transportaion;
            endforeach;
            $netTotal += $grand_total;
        endif;
        return $netTotal;
    }
}

if (! function_exists('invoicesSales')) {
    function invoicesSales($month = '', $year)
    {
        switch ($month) {
            case '1':
                $Invoices = Invoice::whereBetween('dated', [Carbon::now()->subMonth(1), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                break;
            case '3':
                $Invoices = Invoice::whereBetween('dated', [Carbon::now()->subMonth(3), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                break;
            case '6':
                $Invoices = Invoice::whereBetween('dated', [Carbon::now()->subMonth(6), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                break;
            case '12':
                $Invoices = Invoice::whereBetween('dated', [Carbon::now()->subMonth(12), Carbon::now()])->whereYear('dated', $year)->latest()->get();
                break;
            default:
                $Invoices = Invoice::latest()->get();
                break;
        }
        
        $netTotal = 0;
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
                $qty = 0;
                $unitPrice = 0;
                $totalPrice1 = 0;
                foreach($InvoiceProducts as $InvoiceProduct):
                    $qty       += $InvoiceProduct->qty;
                    $unitPrice = $InvoiceProduct->unit_price;
                    $totalPrice1 += $InvoiceProduct->qty*$unitPrice;
                endforeach;

                $totalPrice2 = 0;
                if($Invoice->other_products_name):
                    $moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
                    $moreProductsQty   = explode('@&%$# ', $Invoice->other_products_qty);
                    $moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
                    $moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
                    $count2 = 0;
                    foreach($moreProductsNames as $moreP):
                        $qty            =  $moreProductsQty[$count2]; 
                        $price          =  $moreProductsPrice[$count2];
                        if(is_numeric($qty) && is_numeric($price)):
                        $totalPrice2    += $qty*$price; 
                        endif;
                        $count2++; 
                    endforeach;
                endif;

                $totalPrice = $totalPrice1+$totalPrice2; 
                
                // GST
                if($Invoice->GST == 'on'):
                    $tax_rate = $Invoice->tax_rate/100;
                    $tax = $totalPrice*$tax_rate;
                else:
                    $tax = 0;
                endif;

                $Net_totalPrice = $totalPrice+$tax;
                
                if(isset($Invoice->discount_percent)):
                    $discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
                elseif(isset($Quote->discount_fixed)):
                    $discount_value    = $Invoice->discount_fixed;
                else:
                    $discount_value = 0;
                endif;
                

                // Transport
                if(isset($Invoice->transportaion)):
                    $transportaion = $Invoice->transportaion;
                else:
                    $transportaion = 0;
                endif;
                
                $netTotal  += $Net_totalPrice+$transportaion-$discount_value;
            endforeach;
        endif;
        return $netTotal;
    }
}

if (! function_exists('pendingInvoices')) {
    function pendingInvoices($month = '', $year)
    {
        switch ($month) {
            case '1':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(1), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                break;
            case '3':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(3), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                break;
            case '6':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(6), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                break;
            case '12':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(12), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                break;
            default:
                $Invoices = Invoice::where('payment_status', 'Pending')->latest()->get();
                break;
        }
        
        $netTotal = 0;
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
                $qty = 0;
                $unitPrice = 0;
                $totalPrice1 = 0;
                foreach($InvoiceProducts as $InvoiceProduct):
                    $qty       += $InvoiceProduct->qty;
                    $unitPrice = $InvoiceProduct->unit_price;
                    $totalPrice1 += $InvoiceProduct->qty*$unitPrice;
                endforeach;

                $totalPrice2 = 0;
                if($Invoice->other_products_name):
                    $moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
                    $moreProductsQty   = explode('@&%$# ', $Invoice->other_products_qty);
                    $moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
                    $moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
                    $count2 = 0;
                    foreach($moreProductsNames as $moreP):
                        $qty            =  $moreProductsQty[$count2]; 
                        $price          =  $moreProductsPrice[$count2];
                        if(is_numeric($qty) && is_numeric($price)):
                        $totalPrice2    += $qty*$price; 
                        endif;
                        $count2++; 
                    endforeach;
                endif;

                $totalPrice = $totalPrice1+$totalPrice2; 
                
                // GST
                if($Invoice->GST == 'on'):
                    $tax_rate = $Invoice->tax_rate/100;
                    $tax = $totalPrice*$tax_rate;
                else:
                    $tax = 0;
                endif;

                $Net_totalPrice = $totalPrice+$tax;
                
                if(isset($Invoice->discount_percent)):
                    $discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
                elseif(isset($Quote->discount_fixed)):
                    $discount_value    = $Invoice->discount_fixed;
                else:
                    $discount_value = 0;
                endif;
                

                // Transport
                if(isset($Invoice->transportaion)):
                    $transportaion = $Invoice->transportaion;
                else:
                    $transportaion = 0;
                endif;
                
                $netTotal  += $Net_totalPrice+$transportaion-$discount_value;
            endforeach;
        endif;
        return $netTotal;
    }
}

if (! function_exists('totalExpenses')) {
    function totalExpenses($month = '', $year)
    {
        switch ($month) {
            case '1':
                $total_expenses = Expense::whereBetween('dated', [Carbon::now()->subMonth(1), Carbon::now()])->whereYear('dated', $year)->sum('amount');
                break;
            case '3':
                $total_expenses = Expense::whereBetween('dated', [Carbon::now()->subMonth(3), Carbon::now()])->whereYear('dated', $year)->sum('amount');
                break;
            case '6':
                $total_expenses = Expense::whereBetween('dated', [Carbon::now()->subMonth(6), Carbon::now()])->whereYear('dated', $year)->sum('amount');
                break;
            case '12':
                $total_expenses = Expense::whereBetween('dated', [Carbon::now()->subMonth(12), Carbon::now()])->whereYear('dated', $year)->sum('amount');
                break;
            default:
                $total_expenses = Expense::sum('amount');
                break;
        }
        return $total_expenses; 
    }
}

if (! function_exists('noOfInvoices')) {
    function noOfInvoices($month = '', $year)
    {
        switch ($month) {
            case '1':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(1), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->count();
                break;
            case '3':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(3), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->count();
                break;
            case '6':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(6), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->count();
                break;
            case '12':
                $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [Carbon::now()->subMonth(12), Carbon::now()])
                                    ->whereYear('dated', $year)
                                    ->count();
                break;
            default:
                $Invoices = Invoice::where('payment_status', 'Pending')->count();
                break;
        }
        return $Invoices; 
    }
}

if (! function_exists('getRevenuesByMonth')) {
    function getRevenuesByMonth($month = '')
    {
        $Invoices = Invoice::whereMonth('dated', $month)->whereYear('dated', date('Y'))->latest()->get();
        $netTotal = 0;
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
                $qty = 0;
                $unitPrice = 0;
                $totalPrice1 = 0;
                foreach($InvoiceProducts as $InvoiceProduct):
                    $qty       += $InvoiceProduct->qty;
                    $unitPrice = $InvoiceProduct->unit_price;
                    $totalPrice1 += $InvoiceProduct->qty*$unitPrice;
                endforeach;

                $totalPrice2 = 0;
                if($Invoice->other_products_name):
                    $moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
                    $moreProductsQty   = explode('@&%$# ', $Invoice->other_products_qty);
                    $moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
                    $moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
                    $count2 = 0;
                    foreach($moreProductsNames as $moreP):
                        $qty            =  $moreProductsQty[$count2]; 
                        $price          =  $moreProductsPrice[$count2];
                        if(is_numeric($qty) && is_numeric($price)):
                        $totalPrice2    += $qty*$price; 
                        endif;
                        $count2++; 
                    endforeach;
                endif;

                $totalPrice = $totalPrice1+$totalPrice2; 
                
                // GST
                if($Invoice->GST == 'on'):
                    $tax_rate = $Invoice->tax_rate/100;
                    $tax = $totalPrice*$tax_rate;
                else:
                    $tax = 0;
                endif;

                $Net_totalPrice = $totalPrice+$tax;
                
                if(isset($Invoice->discount_percent)):
                    $discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
                elseif(isset($Quote->discount_fixed)):
                    $discount_value    = $Invoice->discount_fixed;
                else:
                    $discount_value = 0;
                endif;
                

                // Transport
                if(isset($Invoice->transportaion)):
                    $transportaion = $Invoice->transportaion;
                else:
                    $transportaion = 0;
                endif;
                
                $netTotal  += $Net_totalPrice+$transportaion-$discount_value;
            endforeach;
        endif;

        return $netTotal;
    }
}


if (! function_exists('getMonthHighestSales')) {
    function getMonthHighestSales($month = '')
    {
        $sales = [];
        $sales['jan'] = getRevenuesByMonth(1);
        $sales['feb'] = getRevenuesByMonth(2);
        $sales['march'] = getRevenuesByMonth(3);
        $sales['apr'] = getRevenuesByMonth(4);
        $sales['may'] = getRevenuesByMonth(5);
        $sales['june'] = getRevenuesByMonth(6);
        $sales['july'] = getRevenuesByMonth(7);
        $sales['aug'] = getRevenuesByMonth(8);
        $sales['sep'] = getRevenuesByMonth(9);
        $sales['oct'] = getRevenuesByMonth(10);
        $sales['nov'] = getRevenuesByMonth(11);
        $sales['dec'] = getRevenuesByMonth(12);

        return max($sales);
    }
}


if (! function_exists('SalesByProduct')) {
    function SalesByProduct($type = '', $counts = 10)
    {
        switch ($type) {
            case '1':
                $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->whereBetween('invoices.dated', [Carbon::now()->subMonth(1), Carbon::now()])
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
                break;
            case '3':
                $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->whereBetween('invoices.dated', [Carbon::now()->subMonth(3), Carbon::now()])
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
                break;
            case '6':
                $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->whereBetween('invoices.dated', [Carbon::now()->subMonth(6), Carbon::now()])
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
                break;
            case '12':
                $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->whereBetween('invoices.dated', [Carbon::now()->subMonth(12), Carbon::now()])
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
                break;
            default:
                $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
                break;
        }
     
        return $sales;
    }
}

if (! function_exists('SalesByCustomer')) {
    function SalesByCustomer($type = '', $counts = 10)
    {
        switch ($type) {
            case '1':
                $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                ->orderBy('sums','DESC')  
                                ->groupBy('customer_id')
                                ->join('customers','customers.id','=','invoices.customer_id')
                                ->whereBetween('invoices.dated', [Carbon::now()->subMonth(1), Carbon::now()])      
                                ->take($counts)
                                ->get();
                break;
            case '3':
                $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                ->orderBy('sums','DESC')     
                                ->groupBy('customer_id')
                                ->join('customers','customers.id','=','invoices.customer_id')
                                ->whereBetween('invoices.dated', [Carbon::now()->subMonth(3), Carbon::now()])   
                                ->take($counts)
                                ->get();
                break;
            case '6':
                $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                ->orderBy('sums','DESC')       
                                ->groupBy('customer_id')
                                ->join('customers','customers.id','=','invoices.customer_id')
                                ->whereBetween('invoices.dated', [Carbon::now()->subMonth(6), Carbon::now()]) 
                                ->take($counts)
                                ->get();
                break;
            case '12':
                $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                ->orderBy('sums','DESC')      
                                ->groupBy('customer_id')
                                ->join('customers','customers.id','=','invoices.customer_id')
                                ->whereBetween('invoices.dated', [Carbon::now()->subMonth(12), Carbon::now()])  
                                ->take($counts)
                                ->get();
                break;
            default:
                $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                ->orderBy('sums','DESC')    
                                ->groupBy('customer_id')
                                ->join('customers','customers.id','=','invoices.customer_id')    
                                ->take($counts)
                                ->get();
                break;
        }

        return $customers;
    }
}

// if (! function_exists('SalesByCategory')) {
//     function SalesByCategory($type = '')
//     {
//         $catgories_ids = Category::select('id')->get();
//         $products_ids = Product::select('id')->whereIn('category_id', $catgories_ids)->get();
       
//          switch ($type) {
//             case '1':
//                 $sales = DB::table('products')
//                             ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
//                             ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id')
//                             ->selectRaw('products.*, COALESCE(sum(invoice_count),0) total')
//                             ->whereBetween('invoices.dated', [Carbon::now()->subMonth(1), Carbon::now()])
//                             ->whereIn('product_id', $products_ids)
//                             ->groupBy('products.id')
//                             ->orderBy('total','desc')
//                             ->take(12)
//                             ->get();
//                 break;
//             case '3':
//                 $sales = DB::table('products')
//                             ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
//                             ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id')
//                             ->selectRaw('products.*, COALESCE(sum(invoice_count),0) total')
//                             ->whereBetween('invoices.dated', [Carbon::now()->subMonth(3), Carbon::now()])
//                             ->whereIn('product_id', $products_ids)
//                             ->groupBy('products.id')
//                             ->orderBy('total','desc')
//                             ->take(12)
//                             ->get();
//                 break;
//             case '6':
//                 $sales = DB::table('products')
//                             ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
//                             ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id')
//                             ->selectRaw('products.*, COALESCE(sum(invoice_count),0) total')
//                             ->whereBetween('invoices.dated', [Carbon::now()->subMonth(6), Carbon::now()])
//                             ->whereIn('product_id', $products_ids)
//                             ->groupBy('products.id')
//                             ->orderBy('total','desc')
//                             ->take(12)
//                             ->get();
//                 break;
//             case '12':
//                 $sales = DB::table('products')
//                             ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
//                             ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id')
//                             ->selectRaw('products.*, COALESCE(sum(invoice_count),0) total')
//                             ->whereBetween('invoices.dated', [Carbon::now()->subMonth(12), Carbon::now()])
//                             ->whereIn('product_id', $products_ids)
//                             ->groupBy('products.id')
//                             ->orderBy('total','desc')
//                             ->take(12)
//                             ->get();
//                 break;
//             default:
//                 $sales = DB::table('products')
//                             ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
//                             ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id')
//                             ->selectRaw('products.*, COALESCE(sum(invoice_count),0) total')
//                             ->whereIn('product_id', $products_ids)
//                             ->groupBy('products.id')
//                             ->orderBy('total','desc')
//                             ->take(12)
//                             ->get();
//                 break;
//         }

//         return $sales;
//     }
// }

if (! function_exists('getInvoiceSales')) {
    function getInvoiceSales($invoice_id)
    {
        $Invoice = Invoice::find($invoice_id);
        
        $netTotal = 0;

        $InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
        $qty = 0;
        $unitPrice = 0;
        $totalPrice1 = 0;
        foreach($InvoiceProducts as $InvoiceProduct):
            $qty       += $InvoiceProduct->qty;
            $unitPrice = $InvoiceProduct->unit_price;
            $totalPrice1 += $InvoiceProduct->qty*$unitPrice;
        endforeach;

        $totalPrice2 = 0;
        if($Invoice->other_products_name):
            $moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
            $moreProductsQty   = explode('@&%$# ', $Invoice->other_products_qty);
            $moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
            $moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
            $count2 = 0;
            foreach($moreProductsNames as $moreP):
                $qty            =  $moreProductsQty[$count2]; 
                $price          =  $moreProductsPrice[$count2];
                if(is_numeric($qty) && is_numeric($price)):
                $totalPrice2    += $qty*$price; 
                endif;
                $count2++; 
            endforeach;
        endif;

        $totalPrice = $totalPrice1+$totalPrice2; 
        
        // GST
        if($Invoice->GST == 'on'):
            $tax_rate = $Invoice->tax_rate/100;
            $tax = $totalPrice*$tax_rate;
        else:
            $tax = 0;
        endif;

        $Net_totalPrice = $totalPrice+$tax;
        
        if(isset($Invoice->discount_percent)):
            $discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
        elseif(isset($Quote->discount_fixed)):
            $discount_value    = $Invoice->discount_fixed;
        else:
            $discount_value = 0;
        endif;
        

        // Transport
        if(isset($Invoice->transportaion)):
            $transportaion = $Invoice->transportaion;
        else:
            $transportaion = 0;
        endif;
        
        $netTotal  += $Net_totalPrice+$transportaion-$discount_value;
        
        return $netTotal;
    }
}

if (! function_exists('SalesByCategory')) {
    function SalesByCategory($month = '', $year, $category_id)
    {
        $products_ids = Product::select('id')->where('category_id', $category_id)->get();
        $invoice_products_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();
        $invoices_sale = 0;
        switch ($month) {
            case '1':
                $invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereBetween('invoices.dated', [Carbon::now()->subMonth(1), Carbon::now()])
                                    ->whereYear('invoices.dated', $year)
                                    ->get();
                if($invoices->count() > 0):
                    foreach($invoices as $invoice):
                        $invoices_sale += getInvoiceSales($invoice->id);
                    endforeach;
                endif;
                break;
            case '3':
                $invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereBetween('invoices.dated', [Carbon::now()->subMonth(3), Carbon::now()])
                                    ->whereYear('invoices.dated', $year)
                                    ->get();
                if($invoices->count() > 0):
                    foreach($invoices as $invoice):
                        $invoices_sale += getInvoiceSales($invoice->id);
                    endforeach;
                endif;
                break;
            case '6':
                $invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereBetween('invoices.dated', [Carbon::now()->subMonth(6), Carbon::now()])
                                    ->whereYear('invoices.dated', $year)
                                    ->get();
                if($invoices->count() > 0):
                    foreach($invoices as $invoice):
                        $invoices_sale += getInvoiceSales($invoice->id);
                    endforeach;
                endif;
                break;
            case '12':
                $invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereBetween('invoices.dated', [Carbon::now()->subMonth(12), Carbon::now()])
                                    ->whereYear('invoices.dated', $year)
                                    ->get();
                if($invoices->count() > 0):
                    foreach($invoices as $invoice):
                        $invoices_sale += getInvoiceSales($invoice->id);
                    endforeach;
                endif;
                break;
            default:
                $invoices = Invoice::whereIn('id', $invoice_products_ids)->get();
                if($invoices->count() > 0):
                    foreach($invoices as $invoice):
                        $invoices_sale += getInvoiceSales($invoice->id);
                    endforeach;
                endif;
                break;
        }
        
        return $invoices_sale;
    }
}

if (! function_exists('customer')) {
    function customer($customer_id)
    {
        $customer =  Customer::find($customer_id); 
        return $customer;
    }
}