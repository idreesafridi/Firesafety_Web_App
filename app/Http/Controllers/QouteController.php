<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Qoute;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\QouteProducts;
use App\Models\CustomerSpecialPrices;
use App\Models\Branch;
use Auth;
use App\Models\Invoice;
use App\Models\InvoiceProducts;
use App\Models\CashMemo;
use App\Models\Challan;
use App\Models\User;

use App\Models\ViqasEnterpriseQoute;
use App\Models\ViqasEnterpriseQouteProducts;
use App\Models\SafetyCareQoute;
use App\Models\SafetyCareQouteProducts;
use App\Models\TermsAndConditions;
use DB;

class QouteController extends Controller 
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
           
            $Quotes = Qoute::orderBy('id', 'desc')->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Quotes = Qoute::where('branch_id', $user->branch_id)->orderBy('id', 'desc')->get();

        }
        $companies  = Customer::distinct()->get(['company_name']);

        return view('Quote.index', compact('Quotes', 'companies'));
    }
    
    public function DuplicateQuote($id)
    {
        DB::beginTransaction();
        try {
            $Quote = Qoute::find($id);
            $newQuote = $Quote->replicate();
            $newQuote->customer_id = null;
            $newQuote->save();
        
            $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
            
            foreach($QouteProducts as $QouteProduct):
                $newQuoteP = $QouteProduct->replicate();
                $copy = $QouteProduct->replicate()->fill(
                    [
                        'quote_id' => $newQuote->id,
                    ]
                );
                $copy->save();
            endforeach;
        
            DB::commit();
            $notification = array(
                'message' => 'Successfully duplicated Quote',
                'alert-type' => 'success'
            ); 
            return redirect('Quotes')->with($notification);
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                    'message' => 'Failed to duplicated Quote',
                    'alert-type' => 'error'
                );
            return redirect('Quotes')->with($notification);
        }
    }
    
    public function FilterQuote(Request $request)
    {
        $company = $request->company;
        
        $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
        
        if(isset($company)):
            $Quotes = Qoute::whereIn('customer_id', $customer_ids)->latest()->get();
        else:
            $Quotes = Qoute::latest()->get();
        endif;
        
        $companies  = Customer::distinct()->latest()->get(['company_name']); //where('type', 'regular')->
        return view('Quote.index', compact('Quotes', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $qoutes    = DB::table('qoutes')->latest('created_at')->first();
        if(isset($qoutes)):
            $qoutes = $qoutes->id;
        else:
            $invoice_no = 0;
        endif;
        $products   = Product::latest()->get();
        $suppliers  = Supplier::latest()->get();
         $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
       
        if ($user->designation === 'Super Admin') {
            $Branches = Branch::latest()->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Branches = Branch::where('id',$user->branch_id)->latest()->get();

        }

        $selectedBranchId = $user->branch_id;
        $selectedBranch = Branch::find($selectedBranchId);
        
        // Check if the branch was found
        if ($selectedBranch) {
            $selectedBranchName = $selectedBranch->branch_name;
        } else {
            // Handle if the branch was not found
            $selectedBranchName = ''; // Set default value or handle accordingly
        }

        $customers  = Customer::where('type', 'regular')->distinct()->get(['company_name']);
        $TermsAndConditions = TermsAndConditions::latest()->get();
        return view('Quote.create', compact('products', 'suppliers', 'customers', 'TermsAndConditions','qoutes','Branches','selectedBranchName','user'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $customer_company_name    = $request->customer_company_name;
        $company_name  = $request->company_name;
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
        $product_id = $request->product_id;
        $productName = $request->productName[0];
        if(!isset($product_id) AND !isset($productName)):
            $request->validate([
              
            ]);
        endif;
        
        if(!isset($product_id) AND isset($productName)):
            $request->validate([
            ]);
        endif;
      
        $request->validate([
        ]);

        $customer_id = $request->get('customer_id');
        
        // Customer_id = No
        if (!isset($customer_id)) {

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
            // else:
            //     $Customer    = Customer::where('email', $request->get('email'))->first();
            //     $customer_id = $Customer->id;
            // endif;
            $unitPrice = 'product';
        }else{
            $customer_id = $request->get('customer_id');
            $unitPrice = 'discount';
        }

        //attachment
        $fileNameToStore = '';
        if($request->hasFile('attachment')):
            $file = $request->file('attachment');
            // Get filename with extension            
            $filenameWithExt = $request->file('attachment')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('attachment')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/Quote/';
            $file->move($path, $fileNameToStore);
        endif;

        $other_products_imageArray = [];
        if($request->hasFile('other_products_image')):
            $files = $request->file('other_products_image');
            foreach($files as $file):
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $other_products_image = rand(99,99999).time().'.'.$extension;                       
                // Upload Image
                $path = public_path().'/other_products_image/';
                $file->move($path, $other_products_image);
                array_push($other_products_imageArray, $other_products_image);
            endforeach;
        endif;

        $other_products_imageStr = implode('@&%$# ', $other_products_imageArray);

        $Qoute = new Qoute([
            //'supplier_id' => $request->get('supplier_id'),
            'id'      => $request->get('id'),
            'customer_id' => $customer_id,
            'user_id'     => Auth::User()->id,
            // 'branch'             => $request->get('branch'),
            'dated'       => date('Y-m-d'),
            'branch_id' => Auth::User()->branch_id,
            'termsConditions' => $request->termsConditions,
            'other_products_name'   => implode('@&%$# ', $request->productName),
            'other_products_qty'    => implode('@&%$# ', $request->productQty),
            'other_products_price'  => implode('@&%$# ', $request->productPric),
            'other_products_unit'   => implode('@&%$# ', $request->unit),
            'other_products_size'   => implode('@&%$# ', $request->size),
            'other_products_image'  => $other_products_imageStr,
            'other_products_heading'  => implode('@&%$# ', $request->other_products_heading),
            'attachment'            => $fileNameToStore,
            'subject'               =>  $request->subject,
            'GST'                   =>  $request->GST,
            'gst_text'                   =>  $request->gst_text,
            'tax_rate'              => $request->tax_rate,
            'gst_fixed'                   => $request->get('gst_fixed'),
            'WHT'                   =>  $request->WHT,
            'wh_tax'                => $request->wh_tax,
            'transportaion'         =>  $request->transportaion,
            'discount_percent'      => $request->get('discount_percent'),
            'discount_fixed'      => $request->get('discount_fixed'),
            'dated'      => $request->get('dated'),
        ]);
         $Qoute->save();

        if($Qoute->save()):
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
                    $QouteProducts = new QouteProducts([
                        'quote_id'          => $Qoute->id,
                        'product_id'        => $productId,
                        'qty'               => $qtyArray[$count],
                        'unit_price'        => $price[$count],
                        'productCapacity'   => $productCapacity[$count],
                        'sequence'          => isset($sequence[$count]) ? $sequence[$count] : '',
                        'heading'           => isset($heading[$count]) ? $heading[$count] : '',
                        'description'       => isset($description[$count]) ? $description[$count] : '',
                    ]);
                    $QouteProducts->save();
                $count++;
                endforeach;
            endif;

            $notification = array(
                'message' => 'Successfully added Quote',
                'alert-type' => 'success'
            ); 
            return redirect('Quotes')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Quote',
                    'alert-type' => 'error'
                );
            return redirect('Quotes')->with($notification);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function chooseTemplate($id)
    {
        return view('Quote.chooseTemplate', compact('id'));
    }

    public function TemplateOne($id)
    {
        $quote  = Qoute::find($id);

        if(isset($quote->customer_id)):
            $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        else:
            $quoteCustomer  = null;
        endif;

        $QouteProducts   = QouteProducts::where('quote_id', $id)->orderBy('sequence', 'asc')->get();

        if(isset($quote->user_id)):
            $user = User::find($quote->user_id);
        else:
            $user  = null;
        endif;

        //$QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        return view('Quote.TemplateOne', compact('quote', 'quoteCustomer', 'QouteProducts', 'user'));
    }
    public function TemplateTwo($id)
    {
        $quote  = Qoute::find($id);
        if(isset($quote->customer_id)):
            $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        else:
            $quoteCustomer  = null;
        endif;
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        //$QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        return view('Quote.TemplateTwo', compact('quote', 'quoteCustomer', 'QouteProducts'));
    }
    public function TemplateThree($id)
    {
        $quote  = Qoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        //$QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        return view('Quote.TemplateThree', compact('quote', 'quoteCustomer', 'QouteProducts'));
    }
    public function TemplateFour($id)
    {
        $quote  = Qoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        //$QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        return view('Quote.TemplateFour', compact('quote', 'quoteCustomer', 'QouteProducts'));
    }

    public function show($id)
    {
        $quote  = Qoute::find($id);

        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();
        //$QouteSupplier   = Supplier::where('id', $quote->supplier_id)->first();
        return view('Quote.TemplateOne', compact('quote', 'quoteCustomer', 'QouteProducts'));

       // return view('Quote.show', compact('quote', 'quoteCustomer', 'QouteProducts', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products   = Product::latest()->get();
        $suppliers  = Supplier::latest()->get();
        $customers  = Customer::where('type', 'regular')->distinct()->get(['company_name']);
        $quote  = Qoute::find($id);
        $user = auth()->user(); // Assuming you're using Laravel's built-in authentication
       
        if ($user->designation === 'Super Admin') {
            $Branches = Branch::latest()->get();
        } else {
            // Branch admin and staff can only see invoices related to their branch
            $Branches = Branch::where('branch_name', $user->branch)->latest()->get();

        }

        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = QouteProducts::where('quote_id', $id)->get();

        $TermsAndConditions = TermsAndConditions::latest()->get();

        $selectedBranchId = $quote->branch_id;
        $selectedBranch = Branch::find($selectedBranchId);
        
        // Check if the branch was found
        if ($selectedBranch) {
            $selectedBranchName = $selectedBranch->branch_name;
        } else {
            // Handle if the branch was not found
            $selectedBranchName = ''; // Set default value or handle accordingly
        }

        return view('Quote.update', compact('products', 'suppliers', 'customers', 'quote', 'quoteCustomer', 'QouteProducts', 'TermsAndConditions','Branches','selectedBranchName'));
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
          
            $walking = $request->get('walkCustomer');
            if(isset($walking)):
                $Customer = Customer::find($request->get('walkCustomer'));
                if(!isset($Customer)):
                    $Customer = new Customer();
                endif;
                $Customer->customer_name = $request->get('customer_name');
                $Customer->phone_no      = $request->get('phone_no');
                $Customer->email         = $request->get('email');
                $Customer->address       = $request->get('address');
                $Customer->city          = $request->get('city');
                $Customer->company_name  = $request->get('company_name');
                $Customer->dated         = $request->get('dated');
                $Customer->type          = 'walkin';

                $Customer->save();
                $customer_id = $Customer->id;
                $unitPrice = 'product';
            else:
                $Customer = new Customer();
                $Customer->customer_name = $request->get('customer_name');
                $Customer->phone_no      = $request->get('phone_no');
                $Customer->email         = $request->get('email');
                $Customer->address       = $request->get('address');
                $Customer->city          = $request->get('city');
                $Customer->company_name  = $request->get('company_name');
                $Customer->dated  = $request->get('dated');
                $Customer->type          = 'regular';
                $Customer->save();
                $customer_id = $Customer->id;
                $unitPrice = 'product';
            endif;
        }else{
            $customer_id = $request->get('customer_id');
            $unitPrice = 'discount';
        }
        $Qoute = Qoute::find($id);

        $branch = Branch::updateOrCreate(
            ['branch_name' => $request->get('branch')],
            );

        $Qoute->customer_id         = $customer_id;
        $Qoute->termsConditions     = $request->termsConditions;
        $Qoute->subject             = $request->subject;
        $Qoute->transportaion       = $request->transportaion;
        $Qoute->discount_percent    = $request->discount_percent;
        $Qoute->discount_fixed    = $request->discount_fixed;
        $Qoute->branch_id         = $branch->id;
        $Qoute->save();
        if(isset($request->productName)):
            $Qoute->other_products_name  = implode('@&%$# ', $request->productName);
            $Qoute->other_products_qty   = implode('@&%$# ', $request->productQty);
            $Qoute->other_products_price = implode('@&%$# ', $request->productPric);
            $Qoute->other_products_unit  = implode('@&%$# ', $request->unit);
            $Qoute->other_products_size   = implode('@&%$# ', $request->size);
            $Qoute->other_products_heading   = implode('@&%$# ', $request->other_products_heading);
        else:
            $Qoute->other_products_name  = NULL;
            $Qoute->other_products_qty   = NULL;
            $Qoute->other_products_price = NULL;
            $Qoute->other_products_unit  = NULL;
            $Qoute->other_products_size  = NULL;
            $Qoute->other_products_heading  = NULL;
        endif;
        $Qoute->GST                   =  $request->GST;
        $Qoute->gst_text              =  $request->gst_text;
        $Qoute->gst_fixed          = $request->gst_fixed;
        $Qoute->tax_rate              = $request->tax_rate;
        $Qoute->WHT                   =  $request->WHT;
        $Qoute->wh_tax                = $request->wh_tax;
        $Qoute->dated                 =  $request->dated;
        // $Qoute->other_products_image = $other_products_imageStr;

        if($Qoute->save()):
            // QouteProducts
            $productIdsArray  = $request->product_id;
            $qtyArray         = $request->qty;
            $count = 0;

            // delete old data
             QouteProducts::where('quote_id', $Qoute->id)->delete();
                    //create
                    $productIdsArray    = $request->product_id;
                    $qtyArray           = $request->qty;
                    $productCapacityArr = $request->productCapacity;
                    $price              = $request->price; 
                    $sequence           = $request->sequence; 
                    $heading           = $request->heading; 
                    $description        = $request->description;
                    $count1 = 0;
                    foreach($productIdsArray as $productId):
                        if(isset($productId)):
                            $QouteProducts = new QouteProducts([
                                'quote_id'          => $Qoute->id,
                                'product_id'        => $productId,
                                'qty'               => abs($qtyArray[$count1]),
                                'productCapacity'   => $productCapacityArr[$count1],
                                'unit_price'        => $price[$count1],
                                'sequence'          => isset($sequence[$count1]) ? $sequence[$count1]: '',
                                'heading'           => isset($heading[$count1]) ? $heading[$count1] : '',
                                'description'       => isset($description[$count1]) ? $description[$count1] : '',
                            ]);
                            $QouteProducts->save();
                            $count1++;
                        endif;
                    endforeach;

            $notification = array(
                'message' => 'Successfully updated Quote',
                'alert-type' => 'success'
            ); 
            return redirect('Quotes')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Quote',
                    'alert-type' => 'error'
                );
            return redirect('Quotes')->with($notification);
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
        $Qoute = Qoute::find($id);
        if($Qoute->delete()):
            $notification = array(
                'message' => 'Successfully Deleted Quote',
                'alert-type' => 'success'
            ); 
            return redirect('Quotes')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Quote',
                    'alert-type' => 'error'
                );
            return redirect('Quotes')->with($notification);
        endif;
    }

    public function ConvertQuotation($id)
    {
        $Qoute = Qoute::find($id);

        return view('Quote.convertion', compact('id'));
    }

    public function SaveConvertQuotation(Request $request)
    {
        $invoice         = $request->invoice;
        $CashMemo        = $request->CashMemo;
        $DeliveryChallan = $request->DeliveryChallan;
        $IncomingChallan = $request->IncomingChallan;

        $Qoute = Qoute::find($request->id);

        $ok=false;
        if($invoice == 'on'):
            $oldInv     = DB::table('invoices')->latest('created_at')->first();
            if(isset($oldInv)):
                $oldInvNo   = $oldInv->invoice_no;
            else:
                $oldInvNo = 0;
            endif;
            $Invoice = new Invoice([
                'customer_id'       => $Qoute->customer_id,
                'user_id'           => $Qoute->user_id,
                'branch_id'         => Auth::user()->branch_id,
                'dated'             => date('Y-m-d'), //$Qoute->dated
                'termsConditions'   => $Qoute->termsConditions, 
                'quote_id'          => $Qoute->id,
                'GST'               => $Qoute->GST, 
                'gst_text'          => $Qoute->gst_text,
                'tax_rate'          => $Qoute->tax_rate,
                'WHT'               =>  $Qoute->WHT,
                'wh_tax'            => $Qoute->wh_tax,
                'transportaion'     => $Qoute->transportaion,
                'discount_percent'     => $Qoute->discount_percent,
                'discount_fixed'     => $Qoute->discount_fixed,
                'invoice_no'        => $oldInvNo+1,

                'other_products_name'   => $Qoute->other_products_name, 
                'other_products_qty'    => $Qoute->other_products_qty,
                'other_products_price'  => $Qoute->other_products_price,
                'other_products_unit'   => $Qoute->other_products_unit,
                'other_products_size'   => $Qoute->other_products_size,
            ]);
            if($Invoice->save()):
                $QouteProducts = QouteProducts::where('quote_id', $Qoute->id)->get();
                if($QouteProducts->count() > 0):
                foreach($QouteProducts as $QouteProduct): 
                    $InvoiceProducts = new InvoiceProducts([
                        'invoice_id'        => $Invoice->id,
                        'product_id'        => $QouteProduct->product_id,
                        'qty'               => $QouteProduct->qty,
                        'unit_price'        => $QouteProduct->unit_price,
                        'productCapacity'   => $QouteProduct->productCapacity,
                    ]);
                    $InvoiceProducts->save();
                endforeach;
                endif;
                
                $Customer = Customer::find($Qoute->customer_id);
                if($Customer->type != 'regular'):
                    $Customer->type = 'regular';
                    $Customer->save();
                endif;
                $ok=true;
            else:
                $notification = array(
                        'message' => 'Failed to add Invoice',
                        'alert-type' => 'error'
                    );
                return redirect('Invoice')->with($notification);
            endif;
        endif;

        if($CashMemo == 'on'):
            $QouteProducts = QouteProducts::where('quote_id', $Qoute->id)->get();

            $descriptionsArr   = []; 
            $qtyArr            = [];
            $unit_priceArr     = [];
            $total_amountsArr  = [];
            $productCapacity   = [];

            foreach($QouteProducts as $QouteProduct):
                $product    = Product::find($QouteProduct->product_id);
                $des1       = strip_tags($product->description);
                $des2       = str_replace(', ', ' ', $des1);

                array_push($descriptionsArr, $des2);
                array_push($qtyArr, $QouteProduct->qty);
                array_push($unit_priceArr, $QouteProduct->unit_price);
                $qty = $QouteProduct->qty*$QouteProduct->unit_price;
                array_push($total_amountsArr, $qty);

                if(isset($QouteProduct->productCapacity)):
                    array_push($productCapacity, $QouteProduct->productCapacity);
                else:
                    array_push($productCapacity, 1);
                endif;
            endforeach;

            $other_products_name   = explode('@&%$# ', $Qoute->other_products_name);
            $other_products_qty    = explode('@&%$# ', $Qoute->other_products_qty);
            $other_products_price  = explode('@&%$# ', $Qoute->other_products_price);
            $other_products_unit   = explode('@&%$# ', $Qoute->other_products_unit);
            $other_products_size   = explode('@&%$# ', $Qoute->other_products_size);

            $countP = 0;
            if(!empty($other_products_name[0])):
                foreach($other_products_name as $product):
                    array_push($descriptionsArr, $product);
    
                    $QTY = $other_products_qty[$countP];
                    array_push($qtyArr, $QTY);
    
                    $Price = $other_products_price[$countP];
                    array_push($unit_priceArr, floatval($Price));
    
                    $quantity = $QTY*$Price;
    
                    array_push($total_amountsArr, $quantity);
    
                    array_push($productCapacity, 1);
                endforeach;
            endif;


            $descriptions       = implode('@&%$# ', $descriptionsArr);
            $qty                = implode('@&%$# ', $qtyArr);
            $unit_price         = implode('@&%$# ', $unit_priceArr);
            $total_amounts      = implode('@&%$# ', $total_amountsArr);
            $productCapacityStr = implode('@&%$# ', $productCapacity);

            $CashMemo = new CashMemo([
                'customer_order_no'     => '',
                'customer_order_date'   => NULL,
                'reference_no'          => $Qoute->id,
                'created_date'          => date('Y-m-d'),
                'descriptions'          => $descriptions,
                'qty'                   => $qty,
                'unit_price'            => $unit_price,
                'productCapacity'       => $productCapacityStr,
                'total_amounts'         => $total_amounts,
                'customer_id'           => $Qoute->customer_id,
                'user_id'               => $Qoute->user_id,
            ]);
        
            if($CashMemo->save()): 
                $ok=true;
            else:
                $ok=false;
            endif;
        endif;

        if($DeliveryChallan == 'on'):
            $QouteProducts = QouteProducts::where('quote_id', $Qoute->id)->get();

            $descriptionsArr   = [];
            $qtyArr            = [];
            $unitArr           = [];
            $sizeArr           = [];
            $unit_priceArr     = [];
            $total_amountsArr  = [];
            $remarksArr        = [];
            $productCapacity   = [];

            foreach($QouteProducts as $QouteProduct):
                $product = Product::find($QouteProduct->product_id);
                $des1 = strip_tags($product->description);
                $des2 = str_replace(', ', ' ', $des1);
                array_push($descriptionsArr, $des2);
                array_push($qtyArr, $QouteProduct->qty);
                array_push($unitArr, $product->unit); //unit
                array_push($sizeArr, $product->size); //size
                array_push($unit_priceArr, $QouteProduct->unit_price);
                array_push($total_amountsArr, $product->qty);
                array_push($remarksArr, '@&%$# ');
                
                if(isset($QouteProduct->productCapacity)):
                    array_push($productCapacity, $QouteProduct->productCapacity);
                else:
                    array_push($productCapacity, 1);
                endif;
            endforeach;

            $other_products_name   = explode('@&%$# ', $Qoute->other_products_name);
            $other_products_qty    = explode('@&%$# ', $Qoute->other_products_qty);
            $other_products_price  = explode('@&%$# ', $Qoute->other_products_price);
            $other_products_unit   = explode('@&%$# ', $Qoute->other_products_unit);
            $other_products_size   = explode('@&%$# ', $Qoute->other_products_size);

            $countP = 0;
            
            if(!empty($other_products_name[0])):
                foreach($other_products_name as $product):
                    array_push($descriptionsArr, $product);
    
                    $QTY = $other_products_qty[$countP];
                    
             
                    array_push($qtyArr, $QTY);
    
                    $Price = $other_products_price[$countP];
                    array_push($unit_priceArr, floatval($Price));
    
                    $quantity = $QTY*$Price;
    
                    foreach($other_products_price as $productPric):
                        array_push($total_amountsArr, $productPric);
                    endforeach;
    
                    array_push($unitArr, $other_products_unit[$countP]); //unit
                    
                    
                    array_push($sizeArr, $other_products_size[$countP]); //unit
                    
                    array_push($remarksArr, '@&%$# ');
                    array_push($productCapacity, 1);
                endforeach;
            endif;

            $descriptions       = implode('@&%$# ', $descriptionsArr);
            $qty                = implode('@&%$# ', $qtyArr);
            $unit               = implode('@&%$# ', $unitArr);
            $unit_price         = implode('@&%$# ', $unit_priceArr);
            $total_amounts      = implode('@&%$# ', $total_amountsArr);
            $remarks            = implode('@&%$# ', $remarksArr);
            $productCapacityStr = implode('@&%$# ', $productCapacity);

            $Challan = new Challan([
                'customer_order_no'     => '',
                'customer_order_date'   => NULL,
                'reference_no'          => $Qoute->id,
                'created_date'          => date('Y-m-d'),
                'customer_id'       => $Qoute->customer_id,
                'descriptions'      => $descriptions,
                'qty'               => $qty,
                'unit'              => $unit,
                'remarks'           => $remarks,
                'type'              => 'Delivery',
                'productCapacity'  => $productCapacityStr,
                'user_id'     => $Qoute->user_id,
            ]);

            if($Challan->save()):
                $ok=true;
            else:
                $ok=false;
            endif;
        endif;

        if($IncomingChallan == 'on'):
            $QouteProducts = QouteProducts::where('quote_id', $Qoute->id)->get();

            $descriptionsArr   = [];
            $qtyArr            = [];
            $unitArr           = [];
            $sizeArr           = [];
            $unit_priceArr     = [];
            $total_amountsArr  = [];
            $remarksArr        = [];
            $productCapacity   = [];

            foreach($QouteProducts as $QouteProduct):
                $product = Product::find($QouteProduct->product_id);
                
                $des1 = strip_tags($product->description);
                $des2 = str_replace(', ', ' ', $des1);
                array_push($descriptionsArr, $des2);
                
                array_push($qtyArr, $QouteProduct->qty);
                array_push($unitArr, $product->unit); //unit
                array_push($unit_priceArr, $QouteProduct->unit_price);
                array_push($total_amountsArr, $product->qty);
                array_push($remarksArr, '@&%$# ');
                
                if(isset($QouteProduct->productCapacity)):
                    array_push($productCapacity, $QouteProduct->productCapacity);
                else:
                    array_push($productCapacity, 1);
                endif;
            endforeach;

            $other_products_name   = explode('@&%$# ', $Qoute->other_products_name);
            $other_products_qty    = explode('@&%$# ', $Qoute->other_products_qty);
            $other_products_price  = explode('@&%$# ', $Qoute->other_products_price);
            $other_products_unit   = explode('@&%$# ', $Qoute->other_products_unit);
            $other_products_size   = explode('@&%$# ', $Qoute->other_products_size);

            $countP = 0;
            
            if(!empty($other_products_name[0])):
                foreach($other_products_name as $product):
                    array_push($descriptionsArr, $product);
    
                    $QTY = $other_products_qty[$countP];
                    array_push($qtyArr, $QTY);
    
                    $Price = $other_products_price[$countP];
                    array_push($unit_priceArr, floatval($Price));
    
                    $quantity = $QTY*$Price;
    
                    foreach($other_products_price as $productPric):
                        array_push($total_amountsArr, $productPric);
                    endforeach;
    
                    array_push($unitArr, $other_products_unit[$countP]); //unit
                    array_push($sizeArr, $other_products_size[$countP]); //unit
                    
                    array_push($remarksArr, '@&%$# ');
                    array_push($productCapacity, 1);
                endforeach;
            endif;

            $descriptions       = implode('@&%$# ', $descriptionsArr);
            $qty                = implode('@&%$# ', $qtyArr);
            $unit               = implode('@&%$# ', $unitArr);
            $unit_price         = implode('@&%$# ', $unit_priceArr);

            $total_amounts      = implode('@&%$# ', $total_amountsArr);
            $remarks            = implode('@&%$# ', $remarksArr);
            $productCapacityStr = implode('@&%$# ', $productCapacity);

            $Challan = new Challan([ 
                'customer_order_no'     => '',
                'customer_order_date'   => NULL,
                'reference_no'          => $Qoute->id,
                'created_date'          => date('Y-m-d'),
                'customer_id'       => $Qoute->customer_id,
                'descriptions'      => $descriptions,
                'qty'               => $qty,
                'unit'               => $unit,
                'remarks'           => $remarks,
                'type'              => 'Incoming',
                'productCapacity'  => $productCapacityStr,
                'user_id'     => $Qoute->user_id,
            ]);
            
            if($Challan->save()):
                $ok=true;
            else:
                $ok=false;
            endif;
        endif;

        if($ok):
            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('Quotes')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Convert',
                    'alert-type' => 'error'
                );
            return redirect('Quotes')->with($notification);
        endif;
    }

    public function createQuote()
    {
        $products   = Product::latest()->get();
        $suppliers  = Supplier::latest()->get();
        $customers  = Customer::where('type', 'regular')->latest()->get();
        return view('Quote.createQuote', compact('products', 'suppliers', 'customers'));
    }
    

    public function saveQuote(Request $request)
    {
        $customer_id = $request->get('customer_id');

        if (!isset($customer_id)) {
            // check if user already registered
            $checkCustomerCount = Customer::where('email', $request->get('email'))->count();
            if($checkCustomerCount == 0):
                $request->validate([
                    'customer_name'     => 'required',
                    'phone_no'          => 'required|unique:customers',
                    'email'             => 'required|unique:customers',
                    'address'           => 'required',
                    'city'              => 'required',
                    'company_name'      => 'required',
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
            else:
                $Customer    = Customer::where('email', $request->get('email'))->first();
                $customer_id = $Customer->id;
            endif;
            $unitPrice = 'product';
        }else{
            $customer_id = $request->get('customer_id');
            $unitPrice = 'discount';
        }

        //attachment
        $fileNameToStore = '';
        if($request->hasFile('attachment')):
            $file = $request->file('attachment');
            // Get filename with extension            
            $filenameWithExt = $request->file('attachment')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('attachment')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/Quote/';
            $file->move($path, $fileNameToStore);
        endif;

        //
        $Qoute = new Qoute([
            'customer_id' => $customer_id,
            'user_id'     => Auth::User()->id,
            'dated'       => date('Y-m-d'),
            'termsConditions' => $request->termsConditions,
            'attachment'    => $fileNameToStore,
        ]);

        if($Qoute->save()):
            $notification = array(
                'message' => 'Successfully added Quote',
                'alert-type' => 'success'
            ); 
            return redirect('Quotes')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Quote',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }

    public function ConvertToSupportQuote($id)
    {
        return view('Quote.ConvertToSupportQuote', compact('id'));
    }
    public function ConvertToSupportQuoteSave(Request $request)
    {
        $priceViqasEnterprise   = $request->priceViqasEnterprise;
        $priceSafetyCare        = $request->priceSafetyCare;

        // increase percent
        $priceViqasEnterprisePercent   = ($request->priceViqasEnterprise)/100;
        $priceSafetyCarePercent        = ($request->priceSafetyCare)/100;

        $Quote = Qoute::find($request->id);
        $QouteProducts = QouteProducts::where('quote_id', $request->id)->get();

        DB::beginTransaction();

        try {
            // Viqas Enterprise
            $prices     = explode('@&%$# ', $Quote->other_products_price);
            $newPrices  = [];

            foreach($prices as $price):
                if(!empty($price)):
                $priceIncreaseViqas = floatval($priceViqasEnterprisePercent)*$price;
                $netPriceViqas      = $price+$priceIncreaseViqas;
                array_push($newPrices, $netPriceViqas);
                endif;
            endforeach;
            $increasedPrices     = implode('@&%$# ', $newPrices);

            $ViqasEnterpriseQoute = new ViqasEnterpriseQoute([
                'customer_id'           => $Quote->customer_id,
                'user_id'               => $Quote->user_id,
                'dated'                 => date('Y-m-d'),
                'termsConditions'       =>  $Quote->termsConditions,
                'other_products_name'   =>  $Quote->other_products_name,
                'other_products_qty'    =>  $Quote->other_products_qty,
                'other_products_price'  =>  $increasedPrices, //$Quote->other_products_price
                'other_products_unit'   =>  $Quote->other_products_unit,
                'other_products_size'   =>  $Quote->other_products_size,
                'attachment'            =>  $Quote->attachment,
                'subject'               =>   $Quote->subject,
                'GST'                   =>   $Quote->GST,
                'gst_text'              =>   $Quote->gst_text,
                'tax_rate'              =>   $Quote->tax_rate,
                'transportaion'         =>   $Quote->transportaion,
                'increasePercent'       =>   $priceViqasEnterprise,
            ]);
            $ViqasEnterpriseQoute->save();

            // Safety Care

            $prices     = explode('@&%$# ', $Quote->other_products_price);
            $newPrices  = [];

            foreach($prices as $price):
                if(!empty($price)):
                $priceIncreaseSafety = floatval($priceSafetyCarePercent)*$price;
                $netPriceSafety      = $price+$priceIncreaseSafety;
                array_push($newPrices, $netPriceSafety);
                endif;
            endforeach;
            $increasedPrices     = implode('@&%$# ', $newPrices);

            $SafetyCareQoute = new SafetyCareQoute([
                'customer_id'           => $Quote->customer_id,
                'user_id'               => $Quote->user_id,
                'dated'                 => date('Y-m-d'),
                'termsConditions'       =>  $Quote->termsConditions,
                'other_products_name'   =>  $Quote->other_products_name,
                'other_products_qty'    =>  $Quote->other_products_qty,
                'other_products_price'  =>  $increasedPrices, //$Quote->other_products_price
                'other_products_unit'   =>  $Quote->other_products_unit,
                'other_products_size'   =>  $Quote->other_products_size,
                'attachment'            =>  $Quote->attachment,
                'subject'               =>   $Quote->subject,
                'GST'                   =>   $Quote->GST,
                'gst_text'              =>   $Quote->gst_text,
                'tax_rate'              =>   $Quote->tax_rate,
                'transportaion'         =>   $Quote->transportaion,
                'increasePercent'       =>   $priceSafetyCare,
            ]);
            $SafetyCareQoute->save();

            foreach ($QouteProducts as $product):
                // Viqas Enterprise
                $priceIncreaseViqas = $priceViqasEnterprisePercent*$product->unit_price;
                $netPriceViqas      = $product->unit_price+$priceIncreaseViqas;

                $ViqasEnterpriseQouteProducts = new ViqasEnterpriseQouteProducts([
                            'quote_id'    => $SafetyCareQoute->id,
                            'product_id'  => $product->product_id,
                            'qty'         => $product->qty,
                            'unit_price'  => $netPriceViqas,
                            'productCapacity' => $product->productCapacity,
                        ]);
                $ViqasEnterpriseQouteProducts->save();


                // Safety Care
                $priceIncreaseSafety = $priceSafetyCarePercent*$product->unit_price;
                $netPriceSafety      = $product->unit_price+$priceIncreaseSafety;


                $SafetyCareQouteProducts = new SafetyCareQouteProducts([
                            'quote_id'          => $SafetyCareQoute->id,
                            'product_id'        => $product->product_id,
                            'qty'               => $product->qty,
                            'unit_price'        => $netPriceSafety,
                            'productCapacity'   => $product->productCapacity,
                        ]);
                $SafetyCareQouteProducts->save();
            endforeach;

            DB::commit();
            
            $notification = array(
                    'message' => 'Conversion Completed Successfully',
                    'alert-type' => 'success'
                );
            return redirect('Quotes')->with($notification);
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                    'message' => 'Failed to Convert to Supporting Quote',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        }
    }
    
    public function checkForCapcityAjax(Request $request)
    {
        $productID = $request->productID;
        $Product = Product::find($productID);
        
        $capacities = explode(', ', $Product->capacity);
        
        $option = '<option value=""> Select Capcity </option>';
        
        if(isset($Product->capacity)):
            foreach($capacities as $capacity):
                $option .= '<option value="'.htmlentities($capacity).'">'.$capacity.'</option>';
            endforeach;
        endif;
        
        return $option;
    }
    public function loadDescription(Request $request)
    {
        $productID = $request->productID;
        $Product = Product::find($productID);
        return $Product;
    }
    
    public function checkForPriceAjax(Request $request)
    {
        $productID       = $request->productID;
        $productCapacity = $request->productCapacity;
        
        $customer_id       = $request->customer_id;
        
        $Product = Product::find($productID);
        
        $buying_price_per_unit      = explode(', ', $Product->buying_price_per_unit);
        $selling_price_per_unit     = explode(', ', $Product->selling_price_per_unit);
        $capacities                 = explode(', ', $Product->capacity);
        
        $CustomerSpecialPricesCount = CustomerSpecialPrices::where('product_id', $productID)->where('customer_id', $customer_id)->where('productCapacity', $productCapacity)->count();

        if($CustomerSpecialPricesCount > 0):
            $CustomerSpecialPrices = CustomerSpecialPrices::where('product_id', $productID)->where('customer_id', $customer_id)->where('productCapacity', $productCapacity)->first();
            return $CustomerSpecialPrices->discount_price;
        else:
            $count = 0;
            foreach($capacities as $capacity):
                // if($productCapacity == $capacity):
                if(stripos($capacity, $productCapacity) !== FALSE):
                    return $selling_price_per_unit[$count];
                    break;
                endif;
                $count++;
            endforeach;
            
            return 0;
        endif;
    }
    
    
    // check for cities by company name
    public function checkForCityAjax(Request $request)
    {
        $customer_company_name = $request->customer_company_name;
        
        $customers  = Customer::select('city', 'created_at')->where('type', 'regular')->where('company_name', $customer_company_name)->distinct()->latest()->get(['city']);
        
        $option = '<option value=""> Select City </option>';
        
        if($customers->count() > 0):
            foreach($customers as $customer):
                $option .= '<option value="'.$customer->city.'">'.$customer->city.'</option>';
            endforeach;
        endif;
        
        return $option;
    }
    
    // check for address by city and comany name
    public function checkForAddressAjax(Request $request)
    {
        $customer_city          = $request->customer_city;
        $customer_company_name  = $request->customer_company_name;
        
        $customers  = Customer::select('created_at', 'address')
                                ->where('type', 'regular')
                                ->where('city', $customer_city)
                                ->where('company_name', $customer_company_name)
                                ->distinct()
                                ->latest()
                                ->get(['address']);
        
        $option = '<option value=""> Select Address </option>';
        
        if($customers->count() > 0):
            foreach($customers as $customer):
                $option .= '<option value="'.$customer->address.'">'.$customer->address.'</option>';
            endforeach;
        endif;
        
        return $option;
    }
    
    // customer address by city and company and address
    public function checkForCustomerAjax(Request $request)
    {
        $customer_address       = $request->customer_address;
        $customer_city          = $request->customer_city;
        $customer_company_name  = $request->customer_company_name;
        
        $customers  = Customer::select('created_at', 'address', 'id', 'customer_name')
                                ->where('type', 'regular')
                                ->where('city', $customer_city)
                                ->where('company_name', $customer_company_name)
                                ->where('address', $customer_address)
                                ->distinct()
                                ->latest()
                                ->get(['id', 'customer_name']);
        
        $option = '<option value=""> Select Customer </option>';
    
        if($customers->count() > 0):
            foreach($customers as $customer):
                $option .= '<option value="'.$customer->id.'">'.$customer->customer_name.'</option>';
            endforeach;
        endif;
        
        return $option;
    }
    
    public function checkForCustomerNTNAjax(Request $request)
    {
        
        $customer  = Customer::find($request->customer_id);
        
        return $customer->customer_ntn;
    }
}
