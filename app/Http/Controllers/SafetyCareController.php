<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SafetyCareQoute;
use App\Models\SafetyCareQouteProducts;
use DB;
use App\Models\Customer;
use App\Models\User;

class SafetyCareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Quotes = SafetyCareQoute::latest()->get();
        $companies  = Customer::distinct()->get(['company_name']);
        return view('SafetyCareQoute.index', compact('Quotes', 'companies'));
    }
    
    public function FilterSafetyCare(Request $request)
    {
        $company = $request->company;
        
        $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
        
        if(isset($company)):
            $Quotes = SafetyCareQoute::whereIn('customer_id', $customer_ids)->latest()->get();
        else:
            $Quotes = SafetyCareQoute::latest()->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        
        return view('SafetyCareQoute.index', compact('Quotes', 'companies'));
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
        $quote = SafetyCareQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = SafetyCareQouteProducts::where('quote_id', $id)->get();

        $user = User::find($quote->user_id);

        return view('SafetyCareQoute.show', compact('quote', 'quoteCustomer', 'QouteProducts', 'user')); 
    }

    public function safety_down_with_header($id)
    {
        $quote = SafetyCareQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = SafetyCareQouteProducts::where('quote_id', $id)->get();

        $user = User::find($quote->user_id);

        return view('SafetyCareQoute.safety_down_with_header', compact('quote', 'quoteCustomer', 'QouteProducts', 'user')); 
    }

    public function safety_down_without_header($id)
    {
        $quote = SafetyCareQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = SafetyCareQouteProducts::where('quote_id', $id)->get();

        $user = User::find($quote->user_id);

        return view('SafetyCareQoute.safety_down_without_header', compact('quote', 'quoteCustomer', 'QouteProducts', 'user')); 
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
        DB::beginTransaction();

        try {
            $Qoute = SafetyCareQoute::find($id);
            $Qoute->delete();

            $Products = SafetyCareQouteProducts::where('quote_id', $id)->get();
            foreach ($Products as $Product):
                $Product->delete();
            endforeach;

            DB::commit();
            
            $notification = array(
                'message' => 'Successfully Deleted Quote',
                'alert-type' => 'success'
            ); 
            return redirect('SafetyCare')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();

            $notification = array(
                    'message' => 'Failed to delete Quote',
                    'alert-type' => 'error'
                );
            return redirect('SafetyCare')->with($notification);
        }
    }
}
