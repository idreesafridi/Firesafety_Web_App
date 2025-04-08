<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViqasEnterpriseQoute;
use App\Models\ViqasEnterpriseQouteProducts;
use App\Models\Customer;
use App\Models\User;
use DB;

class ViqasEnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Quotes = ViqasEnterpriseQoute::latest()->get();
        $companies  = Customer::distinct()->get(['company_name']);
        return view('ViqasEnterpriseQoute.index', compact('Quotes', 'companies'));
    }
    
    public function FilterViqasEnterprise(Request $request)
    {
        $company = $request->company;
        
        $customer_ids  = Customer::select('id')->where('company_name', $company)->get('id')->toArray();
        
        if(isset($company)):
            $Quotes = ViqasEnterpriseQoute::whereIn('customer_id', $customer_ids)->latest()->get();
        else:
            $Quotes = ViqasEnterpriseQoute::latest()->get();
        endif;
        
        $companies  = Customer::distinct()->get(['company_name']);
        return view('ViqasEnterpriseQoute.index', compact('Quotes', 'companies'));
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
        $quote = ViqasEnterpriseQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = ViqasEnterpriseQouteProducts::where('quote_id', $id)->get();

        $user = User::find($quote->user_id);

        return view('ViqasEnterpriseQoute.show', compact('quote', 'quoteCustomer', 'QouteProducts', 'user')); 
    }

    public function Viqas_down_with_header($id)
    {
        $quote = ViqasEnterpriseQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = ViqasEnterpriseQouteProducts::where('quote_id', $id)->get();

        $user = User::find($quote->user_id);

        return view('ViqasEnterpriseQoute.Viqas_down_with_header', compact('quote', 'quoteCustomer', 'QouteProducts', 'user'));
    }

    public function Viqas_down_without_header($id)
    {
        $quote = ViqasEnterpriseQoute::find($id);
        $quoteCustomer  = Customer::where('id', $quote->customer_id)->first();
        $QouteProducts   = ViqasEnterpriseQouteProducts::where('quote_id', $id)->get();

        $user = User::find($quote->user_id);

        return view('ViqasEnterpriseQoute.Viqas_down_without_header', compact('quote', 'quoteCustomer', 'QouteProducts', 'user'));
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
            $Qoute = ViqasEnterpriseQoute::find($id);
            $Qoute->delete();

            $Products = ViqasEnterpriseQouteProducts::where('quote_id', $id)->get();

            foreach ($Products as $Product):
                $Product->delete();
            endforeach;

            DB::commit();
            
            $notification = array(
                'message' => 'Successfully Deleted Quote',
                'alert-type' => 'success'
            ); 
            return redirect('ViqasEnterprise')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();

            $notification = array(
                    'message' => 'Failed to delete Quote',
                    'alert-type' => 'error'
                );
            return redirect('ViqasEnterprise')->with($notification);
        }
    }
}
