<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qoute;
use App\Models\Challan;
use App\Models\QouteProducts;
use App\Models\Product;
use App\Models\Customer;

class DeliveryChallanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Challans = Challan::where('type', 'Delivery')->latest()->get();
        $companies  = Customer::distinct()->get(['company_name']);
        return view('DeliveryChallan.index', compact('Challans', 'companies'));
    }
    
    public function DuplicateDeliveryChallan($id)
    {
        $Challan = Challan::find($id);
        $newChallan = $Challan->replicate();
        $newChallan->customer_id = null;
        if($newChallan->save()):
            $notification = array(
                'message' => 'Successfully duplicated Delivery Challan.',
                'alert-type' => 'success'
            ); 
            return redirect('DeliveryChallan')->with($notification);
        else:
            $notification = array(
                'message' => 'Failed to duplicated Delivery Challan.',
                'alert-type' => 'error'
            );
            return redirect('DeliveryChallan')->with($notification);
        endif;
    }
    
    public function FilterDeliveryChallan(Request $request)
    {
        $company = $request->company;
        
        $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
        
        if(isset($company)):
            $Challans = Challan::whereIn('customer_id', $customer_ids)->latest()->get();
        else:
            $Challans = Challan::latest()->get();
        endif;
        
        $companies  = Customer::distinct()->latest()->get(['company_name']);
        
        return view('DeliveryChallan.index', compact('Challans', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Quotes = Qoute::latest()->get();
        $customers  = Customer::where('type', 'regular')->distinct()->get(['company_name']);
        $products   = Product::latest()->get();
        
        return view('DeliveryChallan.create', compact('Quotes', 'customers', 'products'));
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
                'company_name' => 'required',
            ]);
        endif;
       
        $customer_city    = $request->customer_city;
        $city  = $request->city;
        if(!isset($customer_city) AND !isset($city)):
            $request->validate([
                'city' => 'required',
            ]);
        endif;

        $customer_address    = $request->customer_address;
        $address  = $request->address;
        if(!isset($customer_address) AND !isset($address)):
            $request->validate([
                'address' => 'required',
            ]);
        endif;
        
        $customer_id    = $request->customer_id;
        $customer_name  = $request->customer_name;
        
        // customer_id = No && customer_name = No
        if(!isset($customer_id) AND !isset($customer_name)):
            $request->validate([
                'customer_name' => 'required',
            ]);
        endif;
        
        
        $customer_id = $request->get('customer_id');

        if (!isset($customer_id)) {
            $request->validate([
                'customer_name'     => 'required',
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
        }else{
            $customer_id = $request->get('customer_id');
        }

        $customer_order_no   = $request->customer_order_no;
        $customer_order_date = $request->customer_order_date;
        
        // other products
        $descriptionsArr = $request->descriptions;
        $qtyArr = $request->qty;
        $unitArr = $request->unit;
        $remarksArr = $request->remarks;
        $productCapacity    = $request->productCapacity;
        $sequenceArr        = $request->sequence; 
        
        // drop down products
        $productIds = $request->product_id;
        $qtys       = $request->qty;
        if(!empty($productIds)):
            $count = 0;
            foreach($productIds as $producdId):
                $ProductData = Product::find($producdId); 
                
                if(isset($ProductData->description)):
                    $productDes1 = strip_tags($ProductData->description);
                    $productDes2 = str_replace('&nbsp;', ' ', $productDes1);
                    
                    array_push($descriptionsArr, $productDes2);
                    // array_push($qtyArr, $qtys[$count]);
                    array_push($unitArr, $ProductData->unit);
                    array_push($remarksArr, '@&%$# ');
                    
                    array_push($productCapacity, 1);
                else:
                    $productDes1 = '';
                    $productDes2 = '';
                endif;
                
                $count++;
            endforeach;
            $qty            = implode('@&%$# ', $qtyArr);
            $unit           = implode('@&%$# ', $unitArr);
            $remarks        = implode('@&%$# ', $remarksArr);
        endif;
        
        $descriptionsArray = [];
        foreach($descriptionsArr as $desArr):
            array_push($descriptionsArray, str_replace(', ', ' ', $desArr));
        endforeach;
        
        if(isset($descriptionsArray[0])):
            $descriptions   = implode('@&%$# ', $descriptionsArray);
        else:
            $descriptions   = '';
        endif;

        if(isset($productCapacity)):
            $productCapacityStr  = implode('@&%$# ', $productCapacity);
        else:
            $productCapacityStr  = '';
        endif;

        $Challan = new Challan([
            'customer_order_no'     => $customer_order_no,
            'customer_order_date'   => $customer_order_date, 
            'created_date'       => date('Y-m-d'), 
            'customer_id'       => $customer_id,
            'descriptions'      => $descriptions,
            'qty'               => $qty,
            'unit'              => $unit,
            'remarks'           => $remarks,
            'type'              => 'Delivery',
            'productCapacity'       => $productCapacityStr,
        ]);
        
        if($Challan->save()):
            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('DeliveryChallan')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Converted',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }

    public function DeliveryChallanstore(Request $request)
    {
        $Qoute = Qoute::find($request->quote_id);
        $QouteProducts = QouteProducts::where('quote_id', $Qoute->id)->get();

        $descriptionsArr    = [];
        $qtyArr             = [];
        $unitArr            = [];
        $unit_priceArr      = [];
        $total_amountsArr   = [];
        $remarksArr         = [];
        $productCapacity    = [];
    
        
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
            
            array_push($productCapacity, $QouteProduct->productCapacity);
        endforeach;

        $other_products_name   = explode('@&%$# ', $Qoute->other_products_name);
        $other_products_qty    = explode('@&%$# ', $Qoute->other_products_qty);
        $other_products_price  = explode('@&%$# ', $Qoute->other_products_price);
        $other_products_unit   = explode('@&%$# ', $Qoute->other_products_unit);

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
                array_push($remarksArr, '@&%$# ');
                array_push($productCapacity, 1);
            endforeach;
        endif;

        $descriptions   = implode('@&%$# ', $descriptionsArr);
        $qty            = implode('@&%$# ', $qtyArr);
        $unit           = implode('@&%$# ', $unitArr);
        $unit_price     = implode('@&%$# ', $unit_priceArr);
        $total_amounts  = implode('@&%$# ', $total_amountsArr);
        $remarks        = implode('@&%$# ', $remarksArr);
        $productCapacityStr  = implode('@&%$# ', $productCapacity);

        $Challan = new Challan([
            'reference_no'          => $Qoute->id,
            'customer_order_no'     => $Qoute->id,
            'customer_order_date'   => NULL,
            'created_date'          => date('Y-m-d'), 
            'customer_id'           => $Qoute->customer_id,
            'descriptions'          => $descriptions,
            'qty'                   => $qty,
            'unit'                  => $unit,
            'remarks'               => $remarks,
            'type'                  => 'Delivery',
            'user_id'               => $Qoute->user_id,
            'productCapacity'       => $productCapacityStr,
        ]);
        
        if($Challan->save()):
            $notification = array(
                'message' => 'Successfully Converted',
                'alert-type' => 'success'
            ); 
            return redirect('DeliveryChallan')->with($notification);
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
        $Challan = Challan::find($id);
        return view('DeliveryChallan.show', compact('Challan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Challan = Challan::find($id);
        $customers  = Customer::where('type', 'regular')->distinct()->get(['company_name']);
        return view('DeliveryChallan.update', compact('Challan', 'customers'));
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
        $customer_company_name    = $request->customer_company_name;
        $company_name  = $request->company_name;
        if(!isset($customer_company_name) AND !isset($company_name)):
            $request->validate([
                'company_name' => 'required',
            ]);
        endif;
       
        $customer_city    = $request->customer_city;
        $city  = $request->city;
        if(!isset($customer_city) AND !isset($city)):
            $request->validate([
                'city' => 'required',
            ]);
        endif;

        $customer_address    = $request->customer_address;
        $address  = $request->address;
        if(!isset($customer_address) AND !isset($address)):
            $request->validate([
                'address' => 'required',
            ]);
        endif;
        
        $customer_id    = $request->customer_id;
        $customer_name  = $request->customer_name;
        
        // customer_id = No && customer_name = No
        if(!isset($customer_id) AND !isset($customer_name)):
            $request->validate([
                'customer_name' => 'required',
            ]);
        endif;
        
        
        $customer_id = $request->get('customer_id');

        if (!isset($customer_id)) {
            $request->validate([
                'customer_name'     => 'required',
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
        }else{
            $customer_id = $request->get('customer_id');
        }
        
        $customer_order_no = $request->customer_order_no;
        $customer_order_date = $request->customer_order_date;
        //$customer_id = $request->customer_id;

        $descriptionsArr = $request->descriptions;
        $qtyArr = $request->qty;
        $unitArr = $request->unit;
        $remarksArr = $request->remarks;

        $descriptions   = implode('@&%$# ', $descriptionsArr);
        $qty            = implode('@&%$# ', $qtyArr);
        $unit           = implode('@&%$# ', $unitArr);
        $remarks        = implode('@&%$# ', $remarksArr);

        $Challan = Challan::find($id);

        $Challan->customer_order_no = $customer_order_no;
        $Challan->customer_order_date   = $customer_order_date; 
        $Challan->customer_id           = $customer_id;


        $Challan->descriptions      = $descriptions;
        $Challan->qty               = $qty;
        $Challan->unit               = $unit;
        $Challan->remarks           = $remarks;
        
        if($Challan->save()):
            $notification = array(
                'message' => 'Successfully Updated',
                'alert-type' => 'success'
            ); 
            return redirect('DeliveryChallan')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to Updated',
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
        $Challan = Challan::find($id);
        if($Challan->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Challan',
                'alert-type' => 'success'
            ); 
            return back()->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Challan',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }
}
