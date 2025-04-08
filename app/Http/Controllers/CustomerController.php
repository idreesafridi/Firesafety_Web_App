<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerSpecialPrices;
use Auth;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Customers =  DB::table('customers')
                        ->where('type', 'regular')
                        ->distinct()->get(['company_name']);

         // Customer::where('type', 'regular')->groupBy('company_name')->latest()->get();
        return view('Customer.index', compact('Customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products  = Product::latest()->get();
        return view('Customer.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $customer_name   = $request->get('customer_name');
       $customer_ntn   = $request->get('customer_ntn');
       $phone_no        = $request->get('phone_no');
       $email           = $request->get('email');
       $address         = $request->get('address');
       $city            = $request->get('city');
       $company_name    = $request->get('company_name');

       $customerIds = [];

        $count=0;
        foreach($customer_name as $customerN):
            $checUser = Customer::where('customer_name', $customerN)->where('company_name', $company_name[$count])->where('city', $city[$count])->count();
        
            if($checUser>0):
                $notification = array(
                        'message' => $request->get('customer_name').' is already registered in '.$company_name[$count].' '.$city[$count],
                        'alert-type' => 'error'
                    );
                return back()->with($notification)->withInput($request->all());;
            endif;
            $customer_ntn = $customer_ntn[$count];            
            $phone = $phone_no[$count];
            $email = $email[$count];
            
            // validate phone
            if(isset($phone)):
                $checkPhoneUser = Customer::where('phone_no', $phone)->count();
                if($checkPhoneUser>0):
                    $notification = array(
                            'message' => 'Phone number has already been taken.',
                            'alert-type' => 'error'
                        );
                    return back()->with($notification)->withInput($request->all());;
                endif;
            endif;
            
            // validate email
            if(isset($email)):
                $checkEmailUser = Customer::where('email', $email)->count();
                if($checkEmailUser>0):
                    $notification = array(
                            'message' => 'Email has already been taken.',
                            'alert-type' => 'error'
                        );
                    return back()->with($notification)->withInput($request->all());;
                endif;
            endif;
            
            $request->validate([
                'customer_name'     => 'required',
                'city'              => 'required',
                'company_name'      => 'required',
            ]);

            $Customer = new Customer([
                'customer_name' => $customerN,
                'customer_ntn' => $customer_ntn,
                'phone_no'      => $phone,
                'email'         => $email,
                'address'       => $address[$count],
                'city'          => $city[$count],
                'company_name'  => $request->get('company_name'),
                'type'          => 'regular',
                'dated'         => date('Y-m-d'),
                'user_id'       => Auth::User()->id
            ]);
            $Customer->save();

            array_push($customerIds, $Customer->id);

            $count++;
        endforeach;
        
        // if there are any discount then save
        $productIdsArray     = $request->product_id;
        $discount_priceArray = $request->discount_price;
        $productCapacityArray = $request->productCapacity;
        $count = 0;
        if(!empty($productIdsArray[0])):
            foreach($productIdsArray as $productId):
                $CustomerSpecialPrices = new CustomerSpecialPrices([
                    'product_id'        => $productId,
                    'productCapacity'   => $productCapacityArray[$count],
                    'discount_price'    => $discount_priceArray[$count],
                    'customer_id'       => $Customer->id,
                ]);
  
                $CustomerSpecialPrices->save();
            $count++;
            endforeach;
        endif;

         $notification = array(
            'message' => 'Successfully added Customer',
            'alert-type' => 'success'
        ); 
        return redirect('Customer')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CustomerData  = Customer::find($id);
        $Customers  = Customer::where('company_name', $CustomerData->company_name)->get();
        return view('Customer.show', compact('Customers', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products  = Product::latest()->get();
        $CustomerData  = Customer::find($id);
        $Customers  = Customer::where('company_name', $CustomerData->company_name)->get();
        return view('Customer.update', compact('products', 'Customers', 'id', 'CustomerData'));
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
            'customer_name'     => 'required',
            'city'              => 'required',
            'company_name'      => 'required',
        ]);
    
        $customer_data = Customer::find($id);

        // delete old
        $customerOldIds = [];
        $CustomersOld  = Customer::where('company_name', $customer_data->company_name)->get();
        foreach($CustomersOld as $CustomersO):
            array_push($customerOldIds, $CustomersO->id);
            //$CustomersO->delete();
        endforeach;

        // add new
        $customer_names = $request->get('customer_name');
        $customer_ntn = $request->get('customer_ntn');
        $phone_nos = $request->get('phone_no');
        $emails = $request->get('email');
        $addresses = $request->get('address');
        $cities = $request->get('city');

        $customerIds = [];

        $count = 0;
        foreach ($CustomersOld as $customer_old) {
            
            $Customer = Customer::find($customer_old->id);
            $Customer->customer_name= $customer_names[$count];
            $Customer->customer_ntn = $customer_ntn[$count];
            $Customer->phone_no     = $phone_nos[$count];
            $Customer->email        = $emails[$count];
            $Customer->address      = $addresses[$count];
            $Customer->city         = $cities[$count];
            $Customer->company_name = $request->company_name;
            $Customer->save();

            array_push($customerIds, $Customer->id);

            $count++;
        }


            // if($Customer->save()):
                // if there are any discount then save
                $productIdsArray     =  $request->product_id;
                $discount_priceArray = $request->discount_price;
                $productCapacityArray = $request->productCapacity;
                $count = 0;

                // old delete
                $CustomerSpecialPricesOld = CustomerSpecialPrices::whereIn('customer_id', $customerOldIds)->get();
                foreach($CustomerSpecialPricesOld as $CustomerSpecialPricesO):
                    $CustomerSpecialPricesO->delete();
                endforeach;

                // add new
                $productIdsArray     = $request->product_id;
                $discount_priceArray = $request->discount_price;
                $productCapacityArray = $request->productCapacity;
                $count = 0;
                if(!empty($productIdsArray[0])):
                    foreach($productIdsArray as $productId):
                        if(!empty($productId)):
                            $CustomerSpecialPrices = new CustomerSpecialPrices([
                                'product_id'        => $productId,
                                'productCapacity'   => $productCapacityArray[$count],
                                'discount_price'    => $discount_priceArray[$count],
                                'customer_id'       => $Customer->id,
                            ]);
                            $CustomerSpecialPrices->save();
                        endif;
                    $count++;
                    endforeach;
                endif;

                $notification = array(
                    'message' => 'Successfully updated Customer',
                    'alert-type' => 'success'
                ); 
                return redirect('Customer')->with($notification);
            // else:
            //     $notification = array(
            //             'message' => 'Failed to update Customer',
            //             'alert-type' => 'error'
            //         );
            //     return redirect('Customer')->with($notification);
            // endif;
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CustomerData  = Customer::find($id);
        $Customers  = Customer::where('company_name', $CustomerData->company_name)->get();

        $ok = false;

        foreach ($Customers as $Customer):
            if ($Customer->delete()) {
                $ok = true;
            }
        endforeach;

        if($ok):
             $notification = array(
                'message' => 'Successfully Deleted Customer',
                'alert-type' => 'success'
            ); 
            return redirect('Customer')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Customer',
                    'alert-type' => 'error'
                );
            return redirect('Customer')->with($notification);
        endif;
    }
}
