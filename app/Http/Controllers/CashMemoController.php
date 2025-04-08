<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qoute;
use App\Models\CashMemo;
use App\Models\QouteProducts; 
use App\Models\Product; 
use App\Models\Customer;  
use App\Models\User;
use App\Models\Branch;
use App\Models\CashmemoProduct;
use Auth;
use Carbon\Carbon;
use DB;


class CashMemoController extends Controller
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

            $CashMemos = CashMemo::latest()->get();

        } else {
            // Branch admin and staff can only see invoices related to their branch

            $CashMemos = CashMemo::where('branch_id', $user->branch_id)->latest()->get();
        }
        $companies  = Customer::distinct()->get(['company_name']);
        
        return view('CashMemo.index', compact('CashMemos', 'companies'));
    }
    
    public function cashmemosByProduct(Request $request)
    {
        $id   = $request->id;
        $from_month  = $request->from_month; 
        $to_month   = $request->to_month;
        
        $product = Product::find($id);
        $products_ids = CashmemoProduct::select('cashmemo_id')->where('product_id', $product->id)->get();
        
        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::whereIn('id', $products_ids)
                                    ->whereBetween('created_date', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::whereIn('id', $products_ids)
                                    ->whereMonth('created_date', $month)
                                    ->whereYear('created_date', $year)
                                    ->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('CashMemo.index', compact('CashMemos', 'companies'));
    }
    
    public function cashmemosByCustomer(Request $request)
    {
        $id   = $request->id;
        $from_month  = $request->from_month; 
        $to_month   = $request->to_month;
        
        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::where('customer_id', $id)
                                    ->whereBetween('created_date', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::where('customer_id', $id)
                                    ->whereMonth('created_date', $month)
                                    ->whereYear('created_date', $year)
                                    ->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('CashMemo.index', compact('CashMemos', 'companies'));
    }
    
    public function cashmemosByCategory(Request $request)
    {
        $id   = $request->id;
        $from_month  = $request->from_month; 
        $to_month   = $request->to_month;

        $products_ids = Product::select('id')->where('category_id', $id)->get();
        $cashmemo_products_ids = CashmemoProduct::select('cashmemo_id')->whereIn('product_id', $products_ids)->get();
       
    
        if($to_month !== 'null'):
            $from = date('Y-m-d', strtotime($from_month));
            $to = date('Y-m-d', strtotime($to_month));
            
            $CashMemos = CashMemo::whereIn('id', $cashmemo_products_ids)
                                    ->whereBetween('created_date', [$from, $to])
                                    ->get();
        else:
            $year = substr($from_month, 0, 4);
            $month = substr($from_month, 5);
            $CashMemos = CashMemo::whereIn('id', $cashmemo_products_ids)
                                    ->whereMonth('created_date', $month)
                                    ->whereYear('created_date', $year)
                                    ->get();
        endif;
                                        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('CashMemo.index', compact('CashMemos', 'companies'));
    }
    
    public function DuplicateCashMemo($id)
    {
        $CashMemo = CashMemo::find($id);
        $newCashMemo = $CashMemo->replicate();
        $newCashMemo->customer_id = null;
        if($newCashMemo->save()):
            $notification = array(
                'message' => 'Successfully duplicated Invoice',
                'alert-type' => 'success'
            ); 
            return redirect('CashMemo')->with($notification);
        else:
            $notification = array(
                'message' => 'Failed to duplicated Invoice',
                'alert-type' => 'error'
            );
            return redirect('CashMemo')->with($notification);
        endif;
    }
    
    public function FilterCashMemo(Request $request)
    {
        $company = $request->company;
        
        $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
        
        if(isset($company)):
            $CashMemos = CashMemo::whereIn('customer_id', $customer_ids)->latest()->get();
        else:
            $CashMemos = CashMemo::latest()->get();
        endif;
        
        $companies  = Customer::distinct()->latest()->get(['company_name']);
        return view('CashMemo.index', compact('CashMemos', 'companies'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $cashmemo    = DB::table('cash_memos')->latest('created_at')->first();
        if(isset($cashmemo)):
            $cashmemo = $cashmemo->id;
        else:
            $cashmemo = 0;
        endif;
        $Quotes    = Qoute::latest()->get();
        $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
       
        if ($user->designation === 'Super Admin') {
            $Branches = Branch::latest()->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Branches = Branch::where('branch_name', $user->branch)->latest()->get();

        }
        $customers  = Customer::distinct()->get(['company_name']);
        
        $products   = Product::latest()->get();
        
        return view('CashMemo.create', compact('Quotes', 'customers', 'products','cashmemo','Branches','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $customer_company_name   = $request->customer_company_name;
        $company_name           = $request->company_name;
        if(!isset($customer_company_name) AND !isset($company_name)):
            $request->validate([
            ]);
        endif;
       
        $customer_city    = $request->customer_city;
        $city  = $request->city;
        if(!isset($customer_city) AND !isset($city)):
            $request->validate([
            ]);
        endif;

        $customer_address    = $request->customer_address;
        $address  = $request->address;
        if(!isset($customer_address) AND !isset($address)):
            $request->validate([
            ]);
        endif;
        
        $customer_id    = $request->customer_id;
        $customer_name  = $request->customer_name;
        
        // customer_id = No && customer_name = No
        if(!isset($customer_id) AND !isset($customer_name)):
            $request->validate([
            ]);
        endif;

        $phone_no   = $request->phone_no;
        $email      = $request->email;

        if (!isset($customer_id)) {
            $request->validate([
            ]);

            $Customer = new Customer([
                'customer_name' => $request->get('customer_name'),
                'phone_no'      => $request->get('phone_no'),
                'email'         => $request->get('email'),
                'address'       => $request->get('address'),
                'city'          => $request->get('city'),
                'company_name'  => $request->get('company_name'),
                'type'          => 'walkin',
            ]);
            $Customer->save();
            $customer_id = $Customer->id;
        }else{
            $customer_id = $request->get('customer_id');
        }

        $customer_order_no      = $request->customer_order_no; 
        $customer_order_date      = $request->customer_order_date;
        
        
        $descriptionsArr        = [];
        $qtyArr                 = [];
        $per_cylinderArr        = [];
        $productCapacity        = [];
        $total_amountsArr       = [];
        $productCapacityArr     = [];
        $sequenceArr            = [];
        
        $other_products_name    = $request->productName;
        $other_products_qty     = $request->productQty;
        $other_products_price   = $request->productPric;
        $other_products_unit    =  $request->unit;
        $sequence               =  $request->sequence;

        
        $countP = 0;
        if(!empty($other_products_name[0])):
        foreach($other_products_name as $product):
            if(isset($product)):
                array_push($descriptionsArr, $product);
    
                $QTY = $other_products_qty[$countP];
                array_push($qtyArr, $QTY);
    
                $Price = $other_products_price[$countP];
                array_push($per_cylinderArr, floatval($Price));
    
                $quantity = $QTY*$Price;
    
                array_push($total_amountsArr, $quantity);
    
                array_push($productCapacityArr, 1);
                
                $sequence = $sequence[$countP];
                array_push($sequenceArr, $sequence);
            endif;
        endforeach;
        endif;

        $descriptions        = implode('@&%$# ', $descriptionsArr);
        $qty                 = implode('@&%$# ', $qtyArr);
        $per_cylinder        = implode('@&%$# ', $per_cylinderArr);
        $productCapacityStr = implode('@&%$# ', $productCapacityArr);
        $sequenceStr        = implode('@&%$# ', $sequenceArr);

        $count1=0;
        $total = 0;
        
        if(isset($descriptionsArr[0])):
            foreach($descriptionsArr as $des):
                $total = $total+$qtyArr[$count1];
                //$count++;
            endforeach;
        endif;
    
        if(!isset($customer_id)):
            return redirect()->back()->with('error', 'Customer is required.')->with($request->all());
        endif;
        
        $CashMemo = new CashMemo([
            'id'      => $request->get('id'),
            'customer_order_date'   => $customer_order_date, 
            'customer_order_no'     => $customer_order_no, 
            'customer_po_no'        => $request->get('customer_po_no'),
            'delievery_challan_no'  => $request->get('delievery_challan_no'),
            'discount_fixed'        => $request->get('discount_fixed'),
            'transportaion'         => $request->get('transportaion'),
            'dated'                 => $request->get('dated'),
            'descriptions'          => $descriptions,
            'qty'                   => $qty,
            'unit_price'            => $per_cylinder,
            'total_amounts'         => $total,
            'customer_id'           => $customer_id,
            'created_date'          => $request->get('dated'),
            'user_id'               => Auth::User()->id,
            'productCapacity'       => $productCapacityStr,
            'sequence'              => $sequenceStr,
            'branch_id'             => Auth::User()->branch_id,
        ]);
        
        if($CashMemo->save()):
            // QouteProducts
            $productIdsArray    = $request->product_id;
            $qtyArray           = $request->qty;
            $unitArray          = $request->unit;
            $productCapacity    = $request->productCapacity;
            $price              = $request->price;
            $sequence           = $request->sequence;
            $heading            = $request->heading;
            $description        = $request->description;
        
            if(isset($productIdsArray[0])):
                $count = 0;
                foreach($productIdsArray as $productId):
                    $CashmemoProduct = new CashmemoProduct([
                        'cashmemo_id'       => $CashMemo->id,
                        'product_id'        => $productId,
                        'qty'               => $qtyArray[$count],
                        'unit_price'        => $price[$count],
                        'productCapacity'   => $productCapacity[$count],
                        'sequence'          => $sequence[$count],
                        'heading'           => isset($heading[$count]) ? $heading[$count] : '',
                        'description'       => isset($description[$count]) ? $description[$count] : '',
                    ]);
                    $CashmemoProduct->save();
                $count++;
                endforeach;
            endif;

            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('CashMemo')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Converted',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }

    public function CashMemoStore(Request $request)
    {
        $Qoute = Qoute::find($request->quote_id);
        $QouteProducts = QouteProducts::where('quote_id', $Qoute->id)->get();

        $descriptionsArr   = [];
        $qtyArr            = [];
        $unit_priceArr     = [];
        $total_amountsArr  = [];

        $productCapacity = [];
        
        foreach($QouteProducts as $QouteProduct):
            $product = Product::find($QouteProduct->product_id);
            
            $des1 = strip_tags($QouteProduct->description);
            $des2 = str_replace(', ', ' ', $des1);
            array_push($descriptionsArr, $des2);
        
            array_push($qtyArr, $QouteProduct->qty);
            array_push($unit_priceArr, $QouteProduct->unit_price);
            $qty = $QouteProduct->qty*$QouteProduct->unit_price;
            array_push($total_amountsArr, $qty);

            array_push($productCapacity, $QouteProduct->productCapacity);
        endforeach;

        $other_products_name   = explode('@&%$# ', $Qoute->other_products_name);
        $other_products_qty    = explode('@&%$# ', $Qoute->other_products_qty);
        $other_products_price  = explode('@&%$# ', $Qoute->other_products_price);
        $other_products_unit   = explode('@&%$# ', $Qoute->other_products_unit);

        $countP = 0;
        
        if(!empty($other_products_name[0])):
            foreach($other_products_name as $product):
                if(isset($product)):
                    array_push($descriptionsArr, $product);
        
                    $QTY = $other_products_qty[$countP];
                    array_push($qtyArr, $QTY);
        
                    $Price = $other_products_price[$countP];
                    array_push($unit_priceArr, floatval($Price));
        
                    $quantity = $QTY*$Price;
        
                    array_push($total_amountsArr, $quantity);
        
                    array_push($productCapacity, 1);
                endif;
            endforeach;
        endif;

        $descriptions   = implode('@&%$# ', $descriptionsArr);
        $qty            = implode('@&%$# ', $qtyArr);
        $unit_price     = implode('@&%$# ', $unit_priceArr);
        $total_amounts  = implode('@&%$# ', $total_amountsArr);
        $productCapacityStr  = implode('@&%$# ', $productCapacity);

        $CashMemo = new CashMemo([
            'quote_id'              => $Qoute->id,
            'reference_no'          => $Qoute->id,
            'customer_order_no'     => $Qoute->id,
            'customer_order_date'   => NULL,
            'descriptions'          => $descriptions,
            'qty'                   => $qty,
            'unit_price'            => $unit_price,
            'total_amounts'         => $total_amounts,
            'customer_id'           => $Qoute->customer_id,
            'created_date'          => date('Y-m-d'), 
            'productCapacity'       => $productCapacityStr
        ]);

        if($CashMemo->save()):
            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('CashMemo')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Converted',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CashMemo = CashMemo::find($id);
        
        $user = User::find($CashMemo->user_id);

        $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $id)->orderBy('sequence', 'asc')->get();
        
        return view('CashMemo.show', compact('CashMemo', 'user', 'CashmemoProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $CashMemo = CashMemo::find($id);
        $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
       
        if ($user->designation === 'Super Admin') {
            $Branches = Branch::latest()->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Branches = Branch::where('id', $user->branch_id)->latest()->get();

        }
        $customers  = Customer::distinct()->get(['company_name']);
        $CashmemoProducts   = CashmemoProduct::where('cashmemo_id', $id)->orderBy('sequence', 'asc')->get();
        $products   = Product::latest()->get();

        $selectedBranchId = $CashMemo->branch_id;
        $selectedBranch = Branch::find($selectedBranchId);
        
        // Check if the branch was found
        if ($selectedBranch) {
            $selectedBranchName = $selectedBranch->branch_name;
        } else {
            // Handle if the branch was not found
            $selectedBranchName = ''; // Set default value or handle accordingly
        }

        return view('CashMemo.update', compact('CashMemo', 'customers', 'CashmemoProducts', 'products','Branches','selectedBranchName'));
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
        $customer_company_name   = $request->customer_company_name;
        $company_name           = $request->company_name;
        if(!isset($customer_company_name) AND !isset($company_name)):
            $request->validate([
            ]);
        endif;
       
        $customer_city    = $request->customer_city;
        $city  = $request->city;
        if(!isset($customer_city) AND !isset($city)):
            $request->validate([
            ]);
        endif;

        $customer_address    = $request->customer_address;
        $address  = $request->address;
        if(!isset($customer_address) AND !isset($address)):
            $request->validate([
            ]);
        endif;
        
        $customer_id    = $request->customer_id;
        $customer_name  = $request->customer_name;
        
        // customer_id = No && customer_name = No
        if(!isset($customer_id) AND !isset($customer_name)):
            $request->validate([
            ]);
        endif;

        $phone_no   = $request->phone_no;
        $email      = $request->email;

        if (!isset($customer_id)) {
            $request->validate([
            ]);

            $Customer = new Customer([
                'customer_name' => $request->get('customer_name'),
                'phone_no'      => $request->get('phone_no'),
                'email'         => $request->get('email'),
                'address'       => $request->get('address'),
                'city'          => $request->get('city'),
                'company_name'  => $request->get('company_name'),
                'type'          => 'walkin',
            ]);
            $Customer->save();
            $customer_id = $Customer->id;
        }else{
            $customer_id = $request->get('customer_id');
        }

        $customer_order_no      = $request->customer_order_no; 
        $customer_order_date      = $request->customer_order_date;
        
        
        $descriptionsArr        = [];
        $qtyArr                 = [];
        $per_cylinderArr        = [];
        $productCapacity        = [];
        $total_amountsArr       = [];
        $productCapacityArr     = [];
        $sequenceArr            = [];
        
        $other_products_name    = $request->productName;
        $other_products_qty     = $request->productQty;
        $other_products_price   = $request->productPric;
        $other_products_unit    =  $request->unit;
        $sequenceOther          =  $request->sequenceOther;
        
        $countP = 0;

        if(!empty($other_products_name[0])):
            foreach($other_products_name as $product):
                if(isset($product)):
                    array_push($descriptionsArr, $product);
        
                    $QTY = $other_products_qty[$countP];
                    array_push($qtyArr, $QTY);
        
                    $Price = $other_products_price[$countP];
                    array_push($per_cylinderArr, floatval($Price));
        
                    $quantity = $QTY*$Price;
        
                    array_push($total_amountsArr, $quantity);
        
                    array_push($productCapacityArr, 1);
                    
                    $sequenceO = $sequenceOther[$countP];
                    
                    array_push($sequenceArr, $sequenceO);
                    
                    $countP++;
                endif;
            endforeach;
        endif;

        
        $descriptions        = implode('@&%$# ', $descriptionsArr);
        $qty                 = implode('@&%$# ', $qtyArr);
        $per_cylinder        = implode('@&%$# ', $per_cylinderArr);
        $productCapacityStr = implode('@&%$# ', $productCapacityArr);
        $sequenceStr        = implode('@&%$# ', $sequenceArr);

        $count1=0;
        $total = 0;
        
        if(isset($descriptionsArr[0])):
            foreach($descriptionsArr as $des):
                $total = $total+$qtyArr[$count1];
                //$count++;
            endforeach;
        endif;
    
        if(!isset($customer_id)):
            return redirect()->back()->with('error', 'Customer is required.')->with($request->all());
        endif;

        $CashMemo = CashMemo::find($id);

        $branch = Branch::updateOrCreate(
            ['branch_name' => $request->get('branch')],
            );

        $CashMemo->customer_order_date   = $customer_order_date; 
        $CashMemo->customer_order_no     = $customer_order_no; 
        $CashMemo->descriptions          = $descriptions;
        $CashMemo->qty                   = $qty;
        $CashMemo->unit_price            = $per_cylinder;
        $CashMemo->total_amounts         = $total;
        $CashMemo->customer_id           = $customer_id;
        $CashMemo->created_date          = $request->date;
        $CashMemo->user_id               = Auth::User()->id;
        $CashMemo->productCapacity       = $productCapacityStr;
        $CashMemo->sequence              = $sequenceStr;
        $CashMemo->discount_percent      = $request->discount_percent;
        $CashMemo->discount_fixed        = $request->discount_fixed;
        $CashMemo->transportaion         = $request->transportaion;
        $CashMemo->dated                 = $request->dated;
        $CashMemo->branch_id             = $branch->id; 
        if($CashMemo->save()):
            
            // delete old products
            $CashmemoProducts = CashmemoProduct::where('cashmemo_id', $id)->get();
            if($CashmemoProducts->count() >0):
                foreach($CashmemoProducts as $CashmemoProduct):
                    $CashmemoProduct->delete();
                endforeach;
            endif;
            
            // QouteProducts
            $productIdsArray    = $request->product_id;
            $qtyArray           = $request->qty;
            $unitArray          = $request->unit;
            $productCapacity    = $request->productCapacity;
            $price              = $request->price;
            $sequence           = $request->sequence;
            $heading           = $request->heading; 
            $description        = $request->description;
            
            if(isset($productIdsArray[0])):
                $count = 0;
                foreach($productIdsArray as $productId):
                    if(isset($productId)):
                        $CashmemoProduct = new CashmemoProduct([
                            'cashmemo_id'       => $CashMemo->id,
                            'product_id'        => $productId,
                            'qty'               => $qtyArray[$count],
                            'unit_price'        => $price[$count],
                            'productCapacity'   => $productCapacity[$count],
                            'sequence'          => $sequence[$count],
                            'heading'           => isset($heading[$count1]) ? $heading[$count1] : '',
                            'description'       => isset($description[$count1]) ? $description[$count1] : '',
                        ]);
                        $CashmemoProduct->save();
                        $count++;
                    endif;
                endforeach;
            endif;

            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('CashMemo')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Converted',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }
    
    public function updateOld(Request $request, $id)
    {
        $customer_order_no      = $request->customer_order_no;
        $customer_id            = $request->customer_id;
        $customer_order_date    = $request->customer_order_date;

        $descriptionsArr    = $request->productName;
        $qtyArr             = $request->productQty;
        $unit_priceArr      = $request->productPric;
        $unitArr            = $request->unit;
        $sequenceArr        = $request->sequence;
      
        $descriptions   = implode('@&%$# ', $descriptionsArr);
        $qty            = implode('@&%$# ', $qtyArr);
        $unit_price     = implode('@&%$# ', $unit_priceArr);
        $unit           = implode('@&%$# ', $unitArr);
        $sequence       = implode('@&%$# ', $sequenceArr);
        
        $customer_name = $request->customer_name;
 
        if(!isset($customer_id) AND !isset($customer_name)):
            return redirect()->back()->with('error', 'Customer is required.')->with($request->all());
        endif;

        $CashMemo = CashMemo::find($id);

        $CashMemo->customer_order_no    = $customer_order_no;
        $CashMemo->customer_order_date  = $customer_order_date; 
        
        if(isset($customer_id)):
            $CashMemo->customer_id          = $customer_id;
        endif;

        $CashMemo->descriptions      = $descriptions;
        $CashMemo->qty               = $qty;
        $CashMemo->unit_price        = $unit_price;
        $CashMemo->productCapacity   = $unit;
        
        if($CashMemo->save()):

            // QouteProducts
            $productIdsArray    = $request->product_id;
            $qtyArray           = $request->qty;
            $unitArray          = $request->unit;
            $productCapacity    = $request->productCapacity;
            $price              = $request->price;
            $sequence           = $request->sequence;
            
            if(isset($productIdsArray[0])):
                $count = 0;
                $CashmemoProductOld = CashmemoProduct::where('cashmemo_id', $CashMemo->id)->get();
                foreach($CashmemoProductOld as $CashmemoProductO):
                    $CashmemoProductO->delete();
                endforeach;
                foreach($productIdsArray as $productId):
                    if(isset($productId)):
                        $CashmemoProduct = new CashmemoProduct([
                            'cashmemo_id'       => $CashMemo->id,
                            'product_id'        => $productId,
                            'qty'               => $qtyArray[$count],
                            'unit_price'        => $price[$count],
                            'productCapacity'   => $productCapacity[$count],
                            'sequence'          => $sequence[$count],
                        ]);
                        $CashmemoProduct->save();
                    endif;
                $count++;
                endforeach;
            endif;

            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('CashMemo')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Converted',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
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
        $CashMemo = CashMemo::find($id);
        if($CashMemo->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Cash Memo',
                'alert-type' => 'success'
            ); 
            return back()->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Cash Memo',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }
}
