<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\InvoiceProducts;
use App\Models\CustomerSpecialPrices;
use App\Models\User;
use App\Models\Branch;
use App\Models\InvoicePayment;
use App\Models\Category;
use Auth;
use Carbon\Carbon;
use PDF;
use DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() 
    {
        $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
       
        if ($user->designation === 'Super Admin') {
           
            $Invoices = Invoice::latest()->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Invoices = Invoice::where('branch_id', $user->branch_id)->latest()->get();
        }
    
        $companies = Customer::distinct()->get(['company_name']);
        return view('Invoice.index', compact('Invoices', 'companies'));
    }

    public function invoicesByProduct(Request $request)
    {
        $id   = $request->id;
        $from_month  = $request->from_month; 
        $to_month   = $request->to_month;
        
        $product = Product::find($id);
        $products_ids = InvoiceProducts::select('invoice_id')->where('product_id', $id)->get();
      
        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::whereIn('id', $products_ids)
                                    ->whereBetween('dated', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            
            $Invoices = Invoice::whereIn('id', $products_ids)
                                    ->whereMonth('dated', $month)
                                    ->whereYear('dated', $year)
                                    ->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.index', compact('Invoices', 'companies'));
    }

    public function invoicesByCustomer(Request $request)
    {
        $id   = $request->id;
        $from_month  = $request->from_month;
        $to_month   = $request->to_month;
        
        // switch ($month) {
        //     case '1':
        //         $FromMonth = new Carbon('first day of January '.$year);
        //         $ToMonth = new Carbon('last day of January '.$year);
                
        //         $Invoices = Invoice::where('customer_id', $id)
        //                             ->whereBetween('invoices.dated', [$FromMonth, $ToMonth])
        //                             ->latest()
        //                             ->get();
        //         break;
        //     case '3':
        //         $FromMonth = new Carbon('first day of January '.$year);
        //         $ToMonth = new Carbon('last day of March '.$year);
                
        //         $Invoices = Invoice::where('customer_id', $id)
        //                             ->whereBetween('invoices.dated', [$FromMonth, $ToMonth])
        //                             ->latest()
        //                             ->get();
        //         break;
        //     case '6':
        //         $FromMonth = new Carbon('first day of January '.$year);
        //         $ToMonth = new Carbon('last day of June '.$year);
                
        //         $Invoices = Invoice::where('customer_id', $id)
        //                             ->whereBetween('invoices.dated', [$FromMonth, $ToMonth])
        //                             ->latest()
        //                             ->get();
        //         break;
        //     case '12':
        //         $FromMonth = new Carbon('first day of January '.$year);
        //         $ToMonth = new Carbon('last day of December '.$year);
                
        //         $Invoices = Invoice::where('customer_id', $id)
        //                             ->whereBetween('invoices.dated', [$FromMonth, $ToMonth])
        //                             ->latest()
        //                             ->get();
        //         break;
        //     default:
        //         $Invoices = Invoice::where('customer_id', $id)
        //                             ->latest()
        //                             ->get();
        //         break;
        // }
        
        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::where('customer_id', $id)
                                    ->whereBetween('invoices.dated', [$from, $to])
                                    ->latest()
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            
            $Invoices = Invoice::where('customer_id', $id)
                                    ->whereMonth('invoices.dated', $month)
                                    ->whereYear('invoices.dated', $year)
                                    ->latest()
                                    ->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.index', compact('Invoices', 'companies'));
    }
    
    public function invoicesByCategory(Request $request)
    {
        $id   = $request->id;
        $from_month  = $request->from_month;
        $to_month   = $request->to_month;
        
        $products_ids = Product::select('id')->where('category_id', $id)->get();
        $invoice_products_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();

        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $Invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereBetween('invoices.dated', [$from, $to])
                                    ->latest()
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            
            $Invoices = Invoice::whereIn('id', $invoice_products_ids)
                                    ->whereMonth('invoices.dated', $month)
                                    ->whereYear('invoices.dated', $year)
                                    ->latest()
                                    ->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('Invoice.index', compact('Invoices', 'companies'));
    }
    
        public function DuplicateInvoice($id)
        {
            DB::beginTransaction();
            try {
 $lastInvoice = Invoice::latest()->first();
        $nextInvoiceNo = $lastInvoice ? $lastInvoice->invoice_no + 1 : 1;

        $originalInvoice = Invoice::find($id);
        $newInvoice = $originalInvoice->replicate();       
        $newInvoice->invoice_no = $nextInvoiceNo;
        $newInvoice->dated = now();
        $newInvoice->customer_id = null;
        $newInvoice->save();        
                $InvoiceProducts = InvoiceProducts::where('invoice_id', $id)->get();
        
                foreach ($InvoiceProducts as $InvoiceP) {
                    $newInvoiceP = $InvoiceP->replicate();
                    $copy = $newInvoiceP->replicate()->fill([
                        'invoice_id' => $newInvoice->id,
                    ]);
                    $copy->save();
                }
        
                DB::commit();
                $notification = array(
                    'message' => 'Successfully duplicated Invoice',
                    'alert-type' => 'success'
                );
                return redirect('Invoice')->with($notification);
            } catch (\Exception $e) {
                DB::rollback();
                $notification = array(
                    'message' => 'Failed to duplicate Invoice',
                    'alert-type' => 'error'
                );
                return redirect('Invoice')->with($notification);
            }
        }

    
    public function FilterInvoice(Request $request)
    {
        $company = $request->company;
        $status = $request->status;

        if(isset($company) && !isset($status)):
            $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
            $Invoices = Invoice::whereIn('customer_id', $customer_ids)->latest()->get();
        elseif(!isset($company) && isset($status)):
            $Invoices = Invoice::where('payment_status', $status)->latest()->get();
        elseif(isset($company) && isset($status)):
            $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
            $Invoices = Invoice::where('payment_status', $status)->whereIn('customer_id', $customer_ids)->latest()->get();
        else:
            $Invoices = Invoice::latest()->get();
        endif;
        
        $companies  = Customer::select('*')->distinct()->latest()->get(['company_name']);
        return view('Invoice.index', compact('companies', 'Invoices'));
    }

    public function status(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        
        $status = $request->status;
            
        if($status == 'Cleared'):
            return redirect()->route('invoice.recieve.payment', $id);
        else:
            return redirect()->route('invoice.payments', $id);
        endif;
        
        $invoice->payment_status = $request->status;
        if($invoice->save()):
            $notification = array(
                'message' => 'Payment status updated successfully.',
                'alert-type' => 'success'
            ); 

            return redirect()->back()->with($notification);
        else:
            $notification = array(
                'message' => 'Something went wrong. please try again.',
                'alert-type' => 'error'
            ); 
            return redirect()->back()->with($notification);
        endif;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoice    = DB::table('invoices')->latest('created_at')->first();
        if(isset($invoice)):
            $invoice_no = $invoice->invoice_no;
        else:
            $invoice_no = 0;
        endif;
        $users   = User::latest()->get();
        $products   = Product::latest()->get();
        $suppliers  = Supplier::latest()->get();
        $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
       
        if ($user->designation === 'Super Admin') {
            $Branches = Branch::latest()->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Branches = Branch::where('branch_name', Auth::user()->branch)->latest()->get();

        }
        $customers  = Customer::where('type', 'regular')->distinct()->get(['company_name']);
        return view('Invoice.create', compact('products', 'suppliers', 'customers', 'invoice_no','users','Branches','user'));
    }
    
    public function expire()
    {
        // get category
        $count = 0;
        $categories = Category::where('expire_invoice', 'yes')->get();
        foreach($categories as $category):
            $products_ids = Product::select('id')->where('category_id', $category->id)->get();
            $invoice_products_ids = InvoiceProducts::select('invoice_id')->whereIn('product_id', $products_ids)->get();
            
            $invoices = Invoice::whereIn('id', $invoice_products_ids)->get();
            if($invoices->count() > 0):
                foreach($invoices as $invoice):
                    $dated = $invoice->dated;
                    
                    $date = new Carbon($dated);

                    $expiry_date = $date->addMonth(11);
                    $expiry_date = substr($expiry_date, 0, 10);
                    
                    $invoice->expiry_date = $expiry_date;
                    $invoice->save();
                    
                    $count++;
                endforeach;
            endif;
        endforeach;
        
        dd('total: '.$count.' done!!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    $request->validate([
        // Add validation rules if needed
    ]);

    $customer_id = $request->get('customer_id');

    if ($request->has('savecustomer') && $request->get('savecustomer') == 'on') {
        // Save as regular type
        $customerType = 'regular';
    } else {
        // Save as walk-in type
        $customerType = 'walkin';
    }

    $Customer = new Customer([
        'customer_name' => $request->get('customer_name'),
        'phone_no'      => $request->get('phone_no'),
        'email'         => $request->get('email'),
        'address'       => $request->get('address'),
        'city'          => $request->get('city'),
        'company_name'  => $request->get('company_name'),
        'type'          => $customerType,
    ]);

    $Customer->save();
    $customer_id = $Customer->id;
        
        if (!isset($customer_id)) {
            // check if user already registered
            $checkCustomerCount = Customer::where('email', $request->get('email'))
                                            ->where('customer_name', $request->get('customer_name'))
                                            ->where('company_name', $request->get('company_name'))
                                            ->where('address', $request->get('address'))
                                            ->where('city', $request->get('city'))
                                            ->count();
  
              
  
            $unitPrice = 'product';
        }else{
            $customer_id = $request->get('customer_id');
            $unitPrice = 'discount';
        }
        
        // $refill = $request->get('refill_notification');
        // if($refill == 'on'):
            $expiry_date = Carbon::now()->addMonth(11);
            $expiry_date = substr($expiry_date, 0, 10);
        // else:
        //     $expiry_date = '';
        // endif;

        $Invoice = new Invoice([
            //'supplier_id' => $request->get('supplier_id'),
            'customer_id'       => $customer_id,
            'user_id'           => Auth::User()->id,
            'branch_id'         => Auth::user()->branch_id,
            'dated'             => date('Y-m-d'),
            'refill_notification'   => $request->get('refill_notification'),
            'sales_tax_invoice'     => $request->get('sales_tax_invoice'),
            'transportaion'         => $request->get('transportaion'),
            'paid_amount'           => 0.00,
            'expiry_date'           => $expiry_date,
            'customer_ntn_no'       => $request->get('customer_ntn_no'),
            'invoice_no'            => $request->get('invoice_no'),
            'customer_po_no'        => $request->get('customer_po_no'),
            'discount_percent'      => $request->get('discount_percent'),
            'discount_fixed'        => $request->get('discount_fixed'),
            'GST'                   => $request->get('GST'),
            'gst_text'                   => $request->get('gst_text'),
            'gst_fixed'                   => $request->get('gst_fixed'),
            'WHT'                   => $request->get('WHT'),
            'tax_rate'              => $request->get('tax_rate'),
            'delievery_challan_no'  => $request->get('delievery_challan_no'),
            'wh_tax'                => $request->get('wh_tax'),
            'dated'                 => $request->get('dated'),
            'other_products_name'   => implode('@&%$# ', $request->productName),
            'other_products_qty'    => implode('@&%$# ', $request->productQty),
            'other_products_price'  => implode('@&%$# ', $request->productPric),
            'other_products_unit'   => implode('@&%$# ', $request->unit),
            'other_products_size'   => implode('@&%$# ', $request->size),
        ]);

        if($Invoice->save()):
            // invoiceProducts
            $productIdsArray    = $request->product_id;
            $qtyArray           = $request->qty;
            $unitArray          = $request->unit;
            $sizeArray          = $request->size;
            $productCapacity    = $request->productCapacity;
            $price              = $request->price;
            $sequence           = $request->sequence;
            $heading            = $request->heading;
            $description        = $request->description;
            $gst_product        = $request->gst_product;
 
           
            
            if(isset($productIdsArray[0])):
                $count = 0;
                foreach($productIdsArray as $productId):
                    $InvoiceProducts = new InvoiceProducts([
                        'invoice_id'    => $Invoice->id,
                        'product_id'  => $productId,
                        'qty'         => $qtyArray[$count],
                        'gst_product' => $gst_product[$count],
                        'unit_price'  => $price[$count],
                        'productCapacity' => $productCapacity[$count],
                        'sequence'          => isset($sequence[$count]) ? $sequence[$count] : '',
                        'heading'           => isset($heading[$count]) ? $heading[$count] : '',
                        'description'       => isset($description[$count]) ? $description[$count] : '',
                    ]);
                  
                    $InvoiceProducts->save();
                    $count++;
                endforeach;
            endif;
            $Invoice->save();

            $notification = array(
                'message' => 'Successfully added Invoice',
                'alert-type' => 'success'
            ); 
            if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin'):
                return redirect('Invoice')->with($notification);
            else:
                return redirect('/')->with($notification);
            endif;
        else:
            $notification = array(
                    'message' => 'Failed to add Invoice',
                    'alert-type' => 'error'
                );
            if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin'):
                return redirect('Invoice')->with($notification);
            else:
                return redirect('/')->with($notification);
            endif;
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($invoiceNo)
    {
        $Invoice = Invoice::where('invoice_no', $invoiceNo)->first();
       
        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $Invoice->id)->orderBy('sequence', 'asc')->get();
        
        $user = User::find($Invoice->user_id);

        return view('Invoice.show', compact('Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'user'));
    }

    public function SalesTaxInvoice($id)
    {
        $Invoice  = Invoice::find($id);

        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $id)->orderBy('sequence', 'asc')->get();
        
        $user = User::find($Invoice->user_id);
        
       // $InvoiceSupplier   = Supplier::where('id', $Invoice->supplier_id)->first();

        return view('Invoice.SalesTaxInvoice', compact('Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::latest()->get();
        $products = Product::latest()->get();
        $suppliers = Supplier::latest()->get();
        $customers = Customer::where('type', 'regular')->latest()->get();
        $Invoice = Invoice::find($id);
        $user = auth()->user();
    
        if ($user->designation === 'Super Admin') {
            $Branches = Branch::latest()->get();
        } else {
            $Branches = Branch::where('branch_name', $user->branch)->latest()->get();
        }
    
        $InvoiceCustomer = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts = InvoiceProducts::where('invoice_id', $id)->get();
    
        $selectedBranchId = $Invoice->branch_id;
        $selectedBranch = Branch::find($selectedBranchId);
        
        // Check if the branch was found
        if ($selectedBranch) {
            $selectedBranchName = $selectedBranch->branch_name;
        } else {
            // Handle if the branch was not found
            $selectedBranchName = ''; // Set default value or handle accordingly
        }

        return view('Invoice.update', compact('products', 'suppliers', 'customers', 'Invoice', 'InvoiceCustomer', 'InvoiceProducts', 'users', 'Branches', 'selectedBranchName'));
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
        $request->validate([
        ]);

        $customer_id = $request->get('customer_company_name'); //customer_id
        if (!isset($customer_id)) {
            $request->validate([
            ]);

            $Customer = Customer::find( $request->get('walkCustomer'));
            if(!isset($Customer)):
                $Customer = new Customer();
            endif;
            
            $Customer->customer_name = $request->get('customer_name');
            $Customer->phone_no      = $request->get('phone_no');
            $Customer->email         = $request->get('email');
            $Customer->address       = $request->get('address');
            $Customer->city          = $request->get('city');
            $Customer->company_name  = $request->get('company_name');
            $Customer->type          =  'walkin';

            $Customer->save();
            $customer_id = $Customer->id;
            $unitPrice = 'product';
        }else{
            $customer_id = $request->get('customer_id');
            $unitPrice = 'discount';
        }


        $Invoice = Invoice::find($id); 
        //new code
        $branch = Branch::updateOrCreate(
            ['branch_name' => $request->get('branch')],
            );



        //$Invoice->supplier_id = $request->get('supplier_id');
        $Invoice->customer_id       = $customer_id;
        $Invoice->termsConditions   = $request->termsConditions;
        $Invoice->customer_ntn_no   = $request->customer_ntn_no; 
        $Invoice->discount_percent  = $request->discount_percent;
        $Invoice->discount_fixed    = $request->discount_fixed;
        $Invoice->invoice_no        = $request->get('invoice_no');
        $Invoice->branch_id         = $branch->id; //new code
        $Invoice->GST               = $request->get('GST');
        $Invoice->gst_text          = $request->get('gst_text');
        $Invoice->gst_fixed          = $request->get('gst_fixed');
        $Invoice->tax_rate          = $request->get('tax_rate');
        $Invoice->WHT               = $request->get('WHT');
        $Invoice->wh_tax            = $request->get('wh_tax');
        $Invoice->customer_po_no    = $request->get('customer_po_no');
        $Invoice->quote_id          = $request->quote_id;
        $Invoice->sales_tax_invoice = $request->get('sales_tax_invoice');
        $Invoice->delievery_challan_no = $request->get('delievery_challan_no');
        $Invoice->dated = $request->get('dated');
        $Invoice->transportaion     = $request->get('transportaion');
        //new code
        $Invoice->save();

        if(isset($request->productName)):
            $Invoice->other_products_name  = implode('@&%$# ', $request->productName);
            $Invoice->other_products_qty   = implode('@&%$# ', $request->productQty);
            $Invoice->other_products_price = implode('@&%$# ', $request->productPric);
            $Invoice->other_products_unit  = implode('@&%$# ', $request->unit);
            $Invoice->other_products_size  = implode('@&%$# ', $request->size);
        else:
            $Invoice->other_products_name  = NULL;
            $Invoice->other_products_qty   = NULL;
            $Invoice->other_products_price = NULL;
            $Invoice->other_products_unit  = NULL;
            $Invoice->other_products_size  = NULL;
        endif;
        
        if($Invoice->save()):
            // QouteProducts
            $productIdsArray    = $request->product_id;
            $qtyArray           = $request->qty;
            $productCapacityArr = $request->productCapacity;
            $price              = $request->price;
            $sequence           = $request->sequence; 
            $heading           = $request->heading; 
            $description        = $request->description;
            $gst_product        = $request->gst_product;
            $count1 = 0;
            $count = 0;
            
            InvoiceProducts::where('invoice_id', $Invoice->id)->delete();
            
            foreach($productIdsArray as $productId):
                if(isset($productId)):
                    // Fetch the product
                    $product = Product::find($productId);
                    if ($product) {
                        // Update the description
                        $product->description = isset($description[$count1]) ? $description[$count1] : $product->description;
                        $product->save();
                    }
                    
                    // Create or update the invoice product
                    $InvoiceProducts = new InvoiceProducts([
                        'invoice_id'          => $Invoice->id,
                        'product_id'        => $productId,
                        'qty'               => abs($qtyArray[$count1]),
                        'gst_product' =>      $gst_product[$count1],
                        'productCapacity'   => (isset($productCapacityArr[$count1])) ? $productCapacityArr[$count1] : '',
                        'unit_price'        => $price[$count1],
                        'sequence'          => $sequence[$count1],
                        'heading'           => isset($heading[$count1]) ? $heading[$count1] : '',
                        'description'       => isset($description[$count1]) ? $description[$count1] : '',
                    ]);
                    $InvoiceProducts->save();
                    $count1++;
                endif;
            endforeach;
            
            $notification = array(
                'message' => 'Successfully updated Invoice',
                'alert-type' => 'success'
            ); 
            if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin'):
                return redirect('Invoice')->with($notification);
            else:
                return redirect('/')->with($notification);
            endif;
        else:
            $notification = array(
                    'message' => 'Failed to update Invoice',
                    'alert-type' => 'error'
                );
            if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin'):
                return redirect('Invoice')->with($notification);
            else:
                return redirect('/')->with($notification);
            endif;
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Invoice = Invoice::find($id);
        if($Invoice->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Invoice',
                'alert-type' => 'success'
            ); 
            return redirect('Invoice')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Invoice',
                    'alert-type' => 'error'
                );
            return redirect('Invoice')->with($notification);
        endif;
    }

    public function ReplicateInvoice(Request $request)
    {
        $ok = false;

        $invoice_id = $request->invoice_id;
        $date       = $request->date;

        $Invoice = Invoice::find($invoice_id);

        $InvoiceNew = new Invoice([
            //'supplier_id' => $Invoice->supplier_id,
            'invoice_no'  => $Invoice->invoice_no+1,
            'customer_id' => $Invoice->customer_id,
            'user_id'     => Auth::User()->id,
            'branch_id'   => Auth::user()->branch_id,
            'dated'       => $date,
            'refill_notification' => $Invoice->refill_notification,
            'sales_tax_invoice'   => $Invoice->sales_tax_invoice,
        ]);

        if($InvoiceNew->save()):
            $InvoiceProducts = InvoiceProducts::where('invoice_id', $Invoice->id)->get();

            foreach($InvoiceProducts as $InvoiceProduct):
                $InvoiceProducts = new InvoiceProducts([
                    'invoice_id'    => $InvoiceNew->id,
                    'product_id'  => $InvoiceProduct->product_id,
                    'qty'         => $InvoiceProduct->qty,
                    'productCapacity' => $InvoiceProduct->productCapacity,
                    'unit_price'  => $InvoiceProduct->unit_price,
                ]);
                
                if($InvoiceProducts->save()):
                    $ok = true;
                else:
                    $ok = false;
                endif;
            endforeach;
        endif;

        if($ok):
            $notification = array(
                'message' => 'Successfully added Invoice',
                'alert-type' => 'success'
            ); 
            return redirect('Invoice')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Invoice',
                    'alert-type' => 'error'
                );
            return redirect('Invoice')->with($notification);
        endif;
    }


    public function DownloadInvoice1($id)
    {
        $Invoice  = Invoice::find($id);

        $InvoiceCustomer  = Customer::where('id', $Invoice->customer_id)->first();
        $InvoiceProducts   = InvoiceProducts::where('invoice_id', $id)->get();
        //$InvoiceSupplier   = Supplier::where('id', $Invoice->supplier_id)->first();
        
        $user = User::find($Invoice->user_id);

        $data = [
           'Invoice'         => $Invoice, 
           'InvoiceCustomer' => $InvoiceCustomer,
           'InvoiceProducts' => $InvoiceProducts,
           'user'            => $user,
        ];

        $pdf = PDF::loadView('Invoice.pdf', $data);
        return $pdf->download('invoice.pdf');
    }
    
    
    public function InvoiceToSales(Request $request, $id)
    {
        $Invoice = Invoice::find($id);

        $Invoice->sales_tax_invoice = 'on';
        
        if($Invoice->save()):
            $notification = array(
                'message' => 'Successfully converted invoice to sales tax invoice.',
                'alert-type' => 'success'
            ); 
            return redirect('Invoice')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to convert Invoice.',
                    'alert-type' => 'error'
                );
            return redirect('Invoice')->with($notification);
        endif;
    }

    public function invoiceRecievePayment($id)
    {
        $invoice = Invoice::find($id);
        $totalAmount = getTotalInvoiceSales($id);
        
        $amount_recieved = $invoice->payments->sum('amount_recieved');
        $wh_tax_recieved          = $invoice->payments->sum('wh_tax');
        $sales_tax_recieved       = $invoice->payments->sum('sales_tax');
        
        $total_amount_recieved = $amount_recieved+$wh_tax_recieved+$sales_tax_recieved;
        
        $remaining_amount = $totalAmount-$total_amount_recieved; //$invoice->payments->sum('amount_recieved');
        return view('Invoice.payments.create', compact('invoice', 'totalAmount', 'remaining_amount', 'amount_recieved', 'wh_tax_recieved', 'sales_tax_recieved', 'total_amount_recieved'));
    }
    public function invoiceviewPayment($id)
    {
        $invoice = Invoice::find($id);
        $totalAmount = getTotalInvoiceSales($id);
        
        $amount_recieved = $invoice->payments->sum('amount_recieved');
        $wh_tax_recieved          = $invoice->payments->sum('wh_tax');
        $sales_tax_recieved       = $invoice->payments->sum('sales_tax');
        
        $total_amount_recieved = $amount_recieved+$wh_tax_recieved+$sales_tax_recieved;
        
        $remaining_amount = $totalAmount-$total_amount_recieved; //$invoice->payments->sum('amount_recieved');
        return view('Invoice.payments.view', compact('invoice', 'totalAmount', 'remaining_amount', 'amount_recieved', 'wh_tax_recieved', 'sales_tax_recieved', 'total_amount_recieved'));
    }

    public function recieveInvocePayment(Request $request, $id)
    {
        $request->validate([
            'amount_recieved'   => 'nullable',
            'remaining_amount'   => 'nullable',
            'wh_tax'            => 'nullable',
            'sales_tax'         => 'nullable',
            'payment_mode'      => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($id);

            $totalAmount = $request->totalAmount;
            $amount_recieved = $request->amount_recieved;
            
            $totalAmount = getTotalInvoiceSales($id);

            // save payment history
            $InvoicePayment = new InvoicePayment();
            $InvoicePayment->invoice_id     = $invoice->id;
            $InvoicePayment->amount_recieved= $request->amount_recieved;
            $InvoicePayment->remaining_amount= $request->remaining_amount;
            $InvoicePayment->wh_tax         = $request->wh_tax;
            $InvoicePayment->sales_tax      = $request->sales_tax;
            $InvoicePayment->payment_mode   = $request->payment_mode;
            $InvoicePayment->dated          = date('Y-m-d');
            $InvoicePayment->recieved_by    = Auth::user()->id;
            $InvoicePayment->save();
            
            $amount_recieved = $invoice->payments->sum('amount_recieved');
            $wh_tax          = $invoice->payments->sum('wh_tax');
            $sales_tax       = $invoice->payments->sum('sales_tax');
            
            $total_amount_recieved = $amount_recieved+$wh_tax+$sales_tax;
            
            $remaining_amount = $totalAmount-$total_amount_recieved; //$invoice->payments->sum('amount_recieved');
            
            // if 100% then update to Cleared
            if ($remaining_amount == 0):
                $payment_status = 'Cleared';
                
                $invoice->payment_status = $payment_status;
                $invoice->save();
            endif;

            DB::commit();
            // all good
            return redirect('/Invoice')->with('success', 'payment recieved successfully');

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->back()->with('error', 'failed! something went wrong.');
        }
    }
    
    public function invocePaymentUpdate($id)
    {
        $payment = InvoicePayment::find($id);
        $invoice = Invoice::find($payment->invoice_id);
        $totalAmount = getTotalInvoiceSales($invoice->id);
        $remaining_amount = $totalAmount-$invoice->payments->sum('amount_recieved');
        return view('Invoice.payments.update', compact('invoice', 'totalAmount', 'remaining_amount', 'payment'));
    }

    public function UpdateInvocePayment(Request $request, $id)
    {
        $request->validate([
            'amount_recieved'   => 'required',
            'wh_tax'            => 'nullable',
            'sales_tax'         => 'required',
            'payment_mode'      => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            $payment = InvoicePayment::find($id);
            $invoice = Invoice::find($payment->invoice_id);

            $totalAmount = $request->totalAmount;
            $amount_recieved = $request->amount_recieved;
            
            $totalAmount = getTotalInvoiceSales($invoice->id);

            // save payment history
            $InvoicePayment = InvoicePayment::find($id);
            $InvoicePayment->invoice_id     = $invoice->id;
            $InvoicePayment->amount_recieved= $request->amount_recieved;
            $InvoicePayment->wh_tax         = $request->wh_tax;
            $InvoicePayment->sales_tax      = $request->sales_tax;
            $InvoicePayment->payment_mode   = $request->payment_mode;
            $InvoicePayment->dated          = date('Y-m-d');
            $InvoicePayment->recieved_by    = Auth::user()->id;
            $InvoicePayment->save();
            
            $remaining_amount = $totalAmount-$invoice->payments->sum('amount_recieved');
            
            // if 100% then update to Cleared
            if ($remaining_amount == 0):
                $payment_status = 'Cleared';
            else:
                $payment_status = 'Pending';
            endif;
            
            $invoice->payment_status = $payment_status;
            $invoice->save();

            DB::commit();
            // all good
            return redirect('/Invoice')->with('success', 'payment recieved successfully');
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->back()->with('error', 'failed! something went wrong.');
        }
    }
    
    public function invoicePayments($id)
    {
        $invoice = Invoice::find($id);

        $qty = 0;
        $unitPrice = 0;
        $totalPrice = 0; 
        $count=1;
        $subtotal = 0;
        if($invoice->products->count() > 0):
        foreach($invoice->products as $product):
            $qty       += $product->qty;
            $unitPrice += $product->unit_price;

            $subtotal = $qty*$unitPrice;
        endforeach;
        endif;

        if($invoice->other_products_name):
            $moreProductsNames  = explode('@&%$# ', $invoice->other_products_name);
            $moreProductsQty    = explode('@&%$# ', $invoice->other_products_qty);
            $moreProductsPrice  = explode('@&%$# ', $invoice->other_products_price);
            $moreProductsUnit   = explode('@&%$# ', $invoice->other_products_unit);
            $moreProductsSize   = explode('@&%$# ', $invoice->other_products_size);
            $count2 = 0;
            foreach($moreProductsNames as $moreP):
                $qty    = $moreProductsQty[$count2]; 
                $price  = $moreProductsPrice[$count2];
                $totalPrice += $qty*$price; 
                $count++; 
                $count2++;
            endforeach;
        endif;

        $Net_totalPrice = $totalPrice+$subtotal;
        $new_total_price = $Net_totalPrice;

        if(isset($invoice->discount_percent) || isset($invoice->discount_fixed)):
            if(isset($invoice->discount_percent)):
                $discount_value     = ($Net_totalPrice / 100) * $invoice->discount_percent;
                $new_total_price    = $Net_totalPrice - $discount_value;
            elseif(isset($invoice->discount_fixed)):
                $discount_value = $invoice->discount_fixed;
                $new_total_price    = $Net_totalPrice - $discount_value;
            endif;
        endif; 

        $subtotal_before_tax = $new_total_price;

        // tax
        $tax_rate = $invoice->tax_rate/100;
        if($invoice->GST == 'on'):
            $tax = $subtotal_before_tax*$tax_rate;
        else:
            $tax = 0;
        endif;

        // transportaion
        if($invoice->transportaion != 0):
            $transportaion  = $invoice->transportaion;
        else:
            $transportaion  = 0;
        endif;

        $subtotal_after_tax = $new_total_price+$tax+$transportaion;
        $totalAmount = $subtotal_after_tax;

        return view('Invoice.payments.index', compact('invoice', 'totalAmount'));
    }
}