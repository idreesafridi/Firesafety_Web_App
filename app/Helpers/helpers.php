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
//use DB;

if (! function_exists('expensesofweek')) {
    function expensesofweek($branch, $date)
    {
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
    function getTotalSales($from_month = '', $to_month = '')
    {
        $invoicesSales = invoicesSales($from_month, $to_month);
        $cashSales = cashSales($from_month, $to_month);
        
        return $invoicesSales+$cashSales;
    }
}


if (! function_exists('invoicesSales')) {
    function invoicesSales($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
       
            $Invoices = Invoice::whereBetween('dated', [$from, $to])->latest()->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::whereMonth('dated', $month)->whereYear('dated', $year)->latest()->get();
        endif;
        
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

if (! function_exists('allinvoicesSalesAmount')) {
    function allinvoicesSalesAmount()
    {
        // Retrieve all invoices without considering any date range
        $invoices = Invoice::latest()->get();
        
        $netTotal = 0;

        if ($invoices->count() > 0) {
            foreach ($invoices as $invoice) {
                $invoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $invoice->id)->get(); 
                $totalPrice1 = 0;

                foreach ($invoiceProducts as $invoiceProduct) {
                    $qty = $invoiceProduct->qty;
                    $unitPrice = $invoiceProduct->unit_price;
                    $totalPrice1 += $qty * $unitPrice;
                }

                $totalPrice2 = 0;

                if ($invoice->other_products_name) {
                    $moreProductsNames = explode('@&%$# ', $invoice->other_products_name);
                    $moreProductsQty = explode('@&%$# ', $invoice->other_products_qty);
                    $moreProductsPrice = explode('@&%$# ', $invoice->other_products_price);
                    $count2 = 0;

                    foreach ($moreProductsNames as $moreP) {
                        $qty = $moreProductsQty[$count2];
                        $price = $moreProductsPrice[$count2];

                        if (is_numeric($qty) && is_numeric($price)) {
                            $totalPrice2 += $qty * $price;
                        }

                        $count2++;
                    }
                }

                $totalPrice = $totalPrice1 + $totalPrice2;

                // GST
                if ($invoice->GST == 'on') {
                    $tax_rate = $invoice->tax_rate / 100;
                    $tax = $totalPrice * $tax_rate;
                } else {
                    $tax = 0;
                }

                $net_totalPrice = $totalPrice + $tax;

                if (isset($invoice->discount_percent)) {
                    $discount_value = ($net_totalPrice / 100) * $invoice->discount_percent;
                } elseif (isset($Quote->discount_fixed)) {
                    $discount_value = $invoice->discount_fixed;
                } else {
                    $discount_value = 0;
                }

                // Transport
                if (isset($invoice->transportaion)) {
                    $transportation = $invoice->transportation;
                } else {
                    $transportation = 0;
                }

                $netTotal += $net_totalPrice + $transportation - $discount_value;
            }
        }

        return $netTotal;
    }
}

if (! function_exists('invoicesSalescount')) {
    function invoicesSalescount($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
       
            $Invoices = Invoice::whereBetween('dated', [$from, $to])->latest()->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::whereMonth('dated', $month)->whereYear('dated', $year)->latest()->get();
        endif;
        $invoicesSalescount = $Invoices->count(); 
        
        return $invoicesSalescount;
    }
}
 

if (! function_exists('cashSales')) {
    function cashSales($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::whereBetween('created_date', [$from, $to])->latest()->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::whereMonth('created_date', $month)->whereYear('created_date', $year)->latest()->get();
        endif;
        
        $netTotal = 0;
        if($CashMemos->count() > 0):
            foreach($CashMemos as $CashMemo):
                $totalPrice=0;
                $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
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
                
                $netTotal += $grand_total;
            endforeach;
        endif;
     
        return $netTotal;
    }
}

if (! function_exists('cashSalescount')) {
    function cashSalescount($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::whereBetween('created_date', [$from, $to])->latest()->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::whereMonth('created_date', $month)->whereYear('created_date', $year)->latest()->get();
        endif;
        $cashSalescount = $CashMemos->count(); 
        
        return $cashSalescount;
    }
}

if (! function_exists('pendingInvoices')) {
    function pendingInvoices($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [$from, $to])
                                    ->latest()
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                                    
        endif;
        
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
                $pendingInvoiceCount = $Invoices->count(); 
            endforeach;
        endif;
        return $netTotal;
    }
}

if (! function_exists('pendingInvoicescount')) {
    function pendingInvoicescount($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereBetween('dated', [$from, $to])
                                    ->latest()
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('payment_status', 'Pending')
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                                    
        endif;
          $pendingInvoiceCount = $Invoices->count(); 
        
        return $pendingInvoiceCount;
    }
}

if (! function_exists('clearedInvoices')) {
    function clearedInvoices($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::where('payment_status', 'Cleared')
                                    ->whereBetween('dated', [$from, $to])
                                    ->latest()
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('payment_status', 'Cleared')
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
        endif;
        
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

if (! function_exists('clearedInvoicecount')) {
    function clearedInvoicecount($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::where('payment_status', 'Cleared')
                                    ->whereBetween('dated', [$from, $to])
                                    ->latest()
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('payment_status', 'Cleared')
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->latest()
                                    ->get();
                                    
        endif;
          $clearedInvoiceCount = $Invoices->count(); 
        
        return $clearedInvoiceCount;
    }
}

if (!function_exists('getPendingInvoices')) {
    function getPendingInvoices($from_month = '', $to_month = '')
    {
        if (!empty($to_month)) {
            $from = date('Y-m-01', strtotime($from_month)); // Start of from_month
            $to = date('Y-m-t', strtotime($to_month)); // End of to_month

            $Invoices = Invoice::where('payment_status', 'Pending')
                ->where('dated', '>=', $from)
                ->where('dated', '<=', $to)
                ->latest()
                ->get();
        } else {
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('payment_status', 'Pending')
                ->whereMonth('dated', $month)
                ->whereYear('dated', $year)
                ->latest()
                ->get();
        }

        return $Invoices;
    }
}

if (!function_exists('getclearedInvoices')) {
    function getclearedInvoices($from_month = '', $to_month = '')
    {
        if (!empty($to_month)) {
            $from = date('Y-m-01', strtotime($from_month)); // Start of from_month
            $to = date('Y-m-t', strtotime($to_month)); // End of to_month

            $Invoices = Invoice::where('payment_status', 'Cleared')
                ->where('dated', '>=', $from)
                ->where('dated', '<=', $to)
                ->latest()
                ->get();
        } else {
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('payment_status', 'Cleared')
                ->whereMonth('dated', $month)
                ->whereYear('dated', $year)
                ->latest()
                ->get();
        }

        return $Invoices;
    }
}

if (! function_exists('totalExpenses')) {
    function totalExpenses($from_month = '', $to_month = '')
    {
        if(isset($from_month)):
            $total_expenses = Expense::whereBetween('dated', [$from_month, $to_month])->sum('amount');
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $total_expenses = Expense::whereMonth('dated', $month)->whereYear('dated', $year)->sum('amount');
        endif;
        
        return $total_expenses; 
    }
}

if (! function_exists('getExpenses')) {
    function getExpenses($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $total_expenses = Expense::whereBetween('dated', [$from, $to])->latest()->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $total_expenses = Expense::whereMonth('dated', $month)->whereYear('dated', $year)->latest()->get();
        endif;
        
        return $total_expenses; 
    }
}

if (!function_exists('noOfInvoices')) {
    function noOfInvoices($from_month = '', $to_month = '')
    {
        $from = date('Y-m-01', strtotime($from_month)); // Start of from_month

        if (!empty($to_month)) {
            $to = date('Y-m-t', strtotime($to_month)); // End of to_month

            $Invoices = Invoice::whereBetween('dated', [$from, $to])->get();
        } else {
            $to = date('Y-m-t', strtotime($from_month)); // End of from_month

            $Invoices = Invoice::where('dated', '>=', $from)
                ->where('dated', '<=', $to)
                ->get();
        }

        return $Invoices->count();
    }
}




if (! function_exists('getNoOfInvoices')) {
    function getNoOfInvoices($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::whereBetween('dated', [$from, $to])->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::whereMonth('dated', $month)->whereYear('dated', $year)->get();
        endif;
        
        return $Invoices; 
    }
}

if (! function_exists('getNoOfCashMemos')) {
    function getNoOfCashMemos($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::whereBetween('created_date', [$from, $to])->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::whereMonth('created_date', $month)->whereYear('created_date', $year)->get();
        endif;
        
        return $CashMemos; 
    }
}

if (! function_exists('getRevenuesByMonth')) {
    function getRevenuesByMonth($from_month = '', $to_month = '')
    {
        $year = substr($from_month, 0, 4);
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::whereBetween('dated', [$from, $to])->latest()->get();
        else:
            $Invoices = Invoice::whereMonth('dated', $from_month)->whereYear('dated', $year)->latest()->get();
        endif;
        
        
        $netTotal = 0;
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
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

if (! function_exists('getRevenuesByMonthCM')) {
    function getRevenuesByMonthCM($from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::whereBetween('created_date', [$from, $to])->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::whereMonth('created_date', $month)->whereYear('created_date', $year)->get();
        endif;
        
        $netTotal = 0;
        if($CashMemos->count() > 0):
            foreach($CashMemos as $CashMemo):
                $totalPrice=0;
                $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
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
                
                $netTotal += $grand_total;
            endforeach;
        endif;
        return $netTotal;
    }
}



if (! function_exists('getRevenuesByMonthYear')) {
    function getRevenuesByMonthYear($from_month = '', $year = '')
    {
        $Invoices = Invoice::whereMonth('dated', $from_month)->whereYear('dated', $year)->latest()->get();
        
        $netTotal = 0;
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
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
if (! function_exists('getRevenuesByMonthYearCM')) {
    function getRevenuesByMonthYearCM($from_month = '', $year = '')
    {
        $CashMemos = CashMemo::whereMonth('created_date', $from_month)->whereYear('created_date', $year)->get();
        
        $netTotal = 0;
        if($CashMemos->count() > 0):
            foreach($CashMemos as $CashMemo):
                $totalPrice=0;
                $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
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
                
                $netTotal += $grand_total;
            endforeach;
        endif;
        return $netTotal;
    }
}


if (! function_exists('getMonthHighestSales')) {
    function getMonthHighestSales($year)
    {
        $sales = [];
        $sales['jan'] = getRevenuesByMonthYear(1, $year);
        $sales['feb'] = getRevenuesByMonthYear(2, $year);
        $sales['march'] = getRevenuesByMonthYear(3, $year);
        $sales['apr'] = getRevenuesByMonthYear(4, $year);
        $sales['may'] = getRevenuesByMonthYear(5, $year);
        $sales['june'] = getRevenuesByMonthYear(6, $year);
        $sales['july'] = getRevenuesByMonthYear(7, $year);
        $sales['aug'] = getRevenuesByMonthYear(8, $year);
        $sales['sep'] = getRevenuesByMonthYear(9, $year);
        $sales['oct'] = getRevenuesByMonthYear(10, $year);
        $sales['nov'] = getRevenuesByMonthYear(11, $year);
        $sales['dec'] = getRevenuesByMonthYear(12, $year);
        return max($sales);
    }
}


/* invoices sales */
if (! function_exists('SalesByProduct')) {
    function SalesByProduct($from_month = '', $to_month = '', $counts = 10)
    {
        if(!empty($to_month)):
        $from = date('Y-m-d', strtotime($from_month));
        $to = date('Y-m-d', strtotime($to_month));
        
        $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->selectRaw('products.*, products.id as product_id') // product id
                            ->selectRaw('invoices.*, invoices.id as invoice_id') // cashmemo id
                            ->whereBetween('invoices.dated', [$from, $to])
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();   
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $sales = DB::table('products')
                            ->leftJoin('invoice_products','products.id','=','invoice_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(invoice_products.qty),0) total')
                            ->leftJoin('invoices','invoices.id','=','invoice_products.invoice_id') // invoice sales amount
                            ->selectRaw('invoices.*') // invoice sales amount
                            ->selectRaw('products.*, products.id as product_id') // product id
                            ->selectRaw('invoices.*, invoices.id as invoice_id') // cashmemo id
                            ->whereMonth('invoices.dated', $month)
                            ->whereYear('invoices.dated', $year)
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
        endif;
        
        return $sales;
    }
} 

/* cash memo sales */
if (! function_exists('CMSalesByProduct')) {
    function CMSalesByProduct($from_month = '', $to_month = '', $counts = 10)
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $sales = DB::table('products')
                            ->leftJoin('cashmemo_products','products.id','=','cashmemo_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(cashmemo_products.qty),0) total')
                            ->leftJoin('cash_memos','cash_memos.id','=','cashmemo_products.cashmemo_id') // cach memo sales amount
                            ->selectRaw('cash_memos.*') // cach memo sales amount
                            ->selectRaw('products.*, products.id as product_id') // product id
                            ->selectRaw('cash_memos.*, cash_memos.id as cash_memo_id') // cashmemo id
                            ->whereBetween('cash_memos.created_date', [$from, $to])
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            
            $sales = DB::table('products')
                            ->leftJoin('cashmemo_products','products.id','=','cashmemo_products.product_id')
                            ->selectRaw('products.*, COALESCE(sum(cashmemo_products.qty),0) total')
                            ->leftJoin('cash_memos','cash_memos.id','=','cashmemo_products.cashmemo_id') // cach memo sales amount
                            ->selectRaw('cash_memos.*') // cach memo sales amount
                            ->selectRaw('products.*, products.id as product_id') // product id
                            ->selectRaw('cash_memos.*, cash_memos.id as cash_memo_id') // cashmemo id
                            ->whereMonth('cash_memos.created_date', $month)
                            ->whereYear('cash_memos.created_date', $year)
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take($counts)
                            ->get();
        endif;
        
        return $sales;
    }
}

if (! function_exists('SalesByCustomer')) {
    function SalesByCustomer($from_month = '', $to_month = '', $counts = 10)
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                ->orderBy('sums','DESC')  
                                ->groupBy('customer_id')
                                ->join('customers','customers.id','=','invoices.customer_id')
                                ->whereBetween('invoices.dated', [$from, $to])    
                                ->take($counts)
                                ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $customers =  Invoice::select('customer_id', 'invoices.id', DB::raw('sum(invoice_count) as sums'))
                                    ->orderBy('sums','DESC')  
                                    ->groupBy('customer_id')
                                    ->join('customers','customers.id','=','invoices.customer_id')
                                    ->whereMonth('invoices.dated', $month)   
                                    ->whereYear('invoices.dated', $year)   
                                    ->take($counts)
                                    ->get();
        endif;
        
        return $customers;
    }
}

if (! function_exists('SalesByCustomerCM')) {
    function SalesByCustomerCM($from_month = '', $to_month = '', $counts = 10)
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $customers =  CashMemo::select('customer_id', 'cash_memos.id', DB::raw('sum(cashmemo_count) as sums'))
                                            ->orderBy('sums','DESC')  
                                            ->groupBy('customer_id')
                                            ->join('customers','customers.id','=','cash_memos.customer_id')
                                            ->whereBetween('cash_memos.created_date', [$from, $to])    
                                            ->take($counts)
                                            ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $customers =  CashMemo::select('customer_id', 'cash_memos.id', DB::raw('sum(cashmemo_count) as sums'))
                                        ->orderBy('sums','DESC')  
                                        ->groupBy('customer_id')
                                        ->join('customers','customers.id','=','cash_memos.customer_id')
                                        ->whereMonth('cash_memos.created_date', $month)    
                                        ->whereYear('cash_memos.created_date', $year)    
                                        ->take($counts)
                                        ->get();
        endif;

        return $customers;
    }
}

if (! function_exists('getInvoiceSales')) {
    function getInvoiceSales($invoice_id)
    {
        $Invoice = Invoice::find($invoice_id);
        $netTotal = 0;
    
        $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 

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


if (! function_exists('getInvoiceSalesByCustomer')) {
    function getInvoiceSalesByCustomer($customer_id, $from_month = '', $to_month = '')
    {
        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::where('customer_id', $customer_id)
                                    ->whereBetween('dated', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::where('customer_id', $customer_id)
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->get();
        endif;
        
        $netTotal = 0;
        
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $netTotal += getInvoiceSales($Invoice->id);
            endforeach;
        endif;
        return $netTotal;
    }
}

if (! function_exists('getInvoiceSalesByProduct')) {
    function getInvoiceSalesByProduct($product_id, $from_month = '', $to_month = '')
    {
        $invoices_ids = InvoiceProducts::select('invoice_id')->where('product_id', $product_id)->get();

        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::whereIn('id', $invoices_ids)
                                    ->whereBetween('dated', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $Invoices = Invoice::whereIn('id', $invoices_ids)
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->get();
        endif;
        
        $netTotal = 0;
        
        if($Invoices->count() > 0):
            foreach($Invoices as $Invoice):
                $qty = 0;
                $unitPrice = 0;
                $totalPrice1 = 0;
                $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get();
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

if (! function_exists('getCashMemoSales')) {
    function getCashMemoSales($cashmemo_id)
    {
        $CashMemo = CashMemo::find($cashmemo_id);
        
        $totalPrice=0;
        $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
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
        
        return $grand_total;
    }
}


if (! function_exists('getCashMemoSalesByCustomer')) {
    function getCashMemoSalesByCustomer($customer_id, $from_month = '', $to_month = '')
    {
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos =  CashMemo::where('customer_id', $customer_id)
                                        ->whereMonth('cash_memos.created_date', [$from, $to])
                                        ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos =  CashMemo::where('customer_id', $customer_id)
                                        ->whereMonth('cash_memos.created_date', $month)
                                        ->whereYear('cash_memos.created_date', $year)
                                        ->get();
        endif;
        
        $grand_total = 0;
        if($CashMemos->count() > 0):
            foreach($CashMemos as $CashMemo):
                $CashMemo = CashMemo::find($CashMemo->id);
                
                $totalPrice=0;
                $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
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
                $grand_total += $new_total_price+$transportaion;
            endforeach;
        endif;
        return $grand_total;
    }
}

if (! function_exists('SalesByCategory')) {
    function SalesByCategory($category_id,$from_month = '',$to_month = '')
    {
        $products_ids = Product::select('id')->where('category_id', $category_id)->get();
        $invoice_products_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();
        $invoices_sale = 0;
        
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereBetween('invoices.dated', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereMonth('invoices.dated', $month)
                                    ->whereYear('invoices.dated', $year)
                                    ->get();
        endif;
        
        if($invoices->count() > 0):
            foreach($invoices as $invoice):
                $invoices_sale += getInvoiceSales($invoice->id);
            endforeach;
        endif;
        
        return $invoices_sale;
    }
}

if (! function_exists('SalesByCategoryCM')) {
    function SalesByCategoryCM($category_id,$from_month = '',$to_month = '')
    {
        $products_ids = Product::select('id')->where('category_id', $category_id)->get();
        $cashmemo_products_ids = CashmemoProduct::select('cashmemo_id')->whereIn('product_id', $products_ids)->get();
        $cashmemo_sale = 0;
        
        if(!empty($to_month)):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos =  CashMemo::whereIn('id', $cashmemo_products_ids)
                                        ->whereBetween('created_date', [$from, $to])
                                        ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos =  CashMemo::whereIn('id', $cashmemo_products_ids)
                                        ->whereMonth('created_date', $month)
                                        ->whereYear('created_date', $year)
                                        ->get();
        endif;
                                
        if($CashMemos->count() > 0):
            foreach($CashMemos as $CashMemo):
                $cashmemo_sale += getCashMemoSales($CashMemo->id);
            endforeach;
        endif;
        
        return $cashmemo_sale;
    }
}

if (! function_exists('customer')) {
    function customer($customer_id)
    {
        $customer =  Customer::find($customer_id); 
        return $customer;
    }
}


/* for accounts */
if (! function_exists('getTotalInvoiceSales')) {
    function getTotalInvoiceSales($invoice_id)
    {
        $netTotal = 0;
        
        $Invoice = Invoice::find($invoice_id);
        
        $qty = 0;
        $unitPrice = 0;
        $totalPrice1 = 0;
        $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get();
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

if (! function_exists('getTotalInvoiceExTax')) {
    function getTotalInvoiceExTax($invoice_id)
    {
        $netTotal = 0;
        
        $Invoice = Invoice::find($invoice_id);
        
        $qty = 0;
        $unitPrice = 0;
        $totalPrice1 = 0;
        $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get();
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

        $Net_totalPrice = $totalPrice;
        
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

if (! function_exists('getInvoiceTaxAmount')) {
    function getInvoiceTaxAmount($invoice_id)
    {
        $netTotal = 0;
        
        $Invoice = Invoice::find($invoice_id);
        
        $qty = 0;
        $unitPrice = 0;
        $totalPrice1 = 0;
        $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get();
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
        
        return $tax;
    }
}