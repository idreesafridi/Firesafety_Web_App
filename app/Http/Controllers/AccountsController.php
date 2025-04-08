<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\paymentHistory;
use DB;
use Auth;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = 'all';
        return redirect()->route('salesReport', ['type'=>$type]);
        
        $payments = paymentHistory::distinct('invoice_id')->latest()->get();
        return view('Accounts.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = 'pending';
        return redirect()->route('salesReport', ['type'=>$type]);
        
        $Invoices = Invoice::latest()->get();
        return view('Accounts.create', compact('Invoices'));
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
            'invoice_id'   => 'required',
            'amount_paid'  => 'required',
        ]);
        DB::beginTransaction();

        try {
            DB::table('invoices')->where('id', $request->get('invoice_id'))->increment('paid_amount', $request->get('amount_paid'));
            // register as credit
            $paymentHistory = new paymentHistory([
                'invoice_id'    => $request->get('invoice_id'),
                'amount_paid'   => $request->get('amount_paid'),
                'dated'         => date('Y-m-d'),
                'comments'      => $request->get('comments'),
                'recieved_by'   => Auth::User()->id,
            ]);
            if($paymentHistory->save()):
                DB::commit();
                $notification = array(
                    'message' => 'Transaction is Succeeded!',
                    'alert-type' => 'success'
                ); 
                return redirect('Accounts')->with($notification);
            endif;
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                'message' => 'Opps! Transaction failed.',
                'alert-type' => 'error'
            ); 
            return back()->with($notification);
        }
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

    public function getAllAmount(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $Invoice = Invoice::find($invoice_id);

        $amountRemaining = $Invoice->total_amount - $Invoice->paid_amount;
        return $amountRemaining;
    }

    public function VerifyPaymentNow($id)
    {
        $payments = paymentHistory::find($id);
        $payments->verified = 'Yes';
        if($payments->save()):
            $notification = array(
                'message' => 'Verfification Succeeded!',
                'alert-type' => 'success'
            ); 
            return back()->with($notification);
        else:
            $notification = array(
                'message' => 'Verfification Failed!',
                'alert-type' => 'error'
            ); 
            return back()->with($notification);
        endif;
    }
}
