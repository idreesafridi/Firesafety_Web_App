<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Suppliers = Supplier::latest()->get();
        return view('Supplier.index', compact('Suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Supplier.create');
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
            'name'       => 'required',
            'address'    => 'required',
            'phone1'      => 'required',
        ]);

        $Supplier = new Supplier([
            'name'       => $request->get('name'),
            'address'    => $request->get('address'),
            'phone1'      => $request->get('phone1'),
            'phone2'      => $request->get('phone2'),
            'mobile1'      => $request->get('mobile1'),
            'mobile2'      => $request->get('mobile2'),
            'user_id'     => Auth::User()->id,
            'dated'       => date('Y-m-d'),
        ]);

        if($Supplier->save()):
             $notification = array(
                'message' => 'Successfully added Supplier',
                'alert-type' => 'success'
            ); 
            return redirect('Supplier')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Supplier',
                    'alert-type' => 'error'
                );
            return redirect('Supplier')->with($notification);
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
        $Supplier = Supplier::find($id);
        return view('Supplier.update', compact('Supplier'));
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
            'name'       => 'required',
            'address'    => 'required',
            'phone1'      => 'required',
        ]);

        $Supplier = Supplier::find($id);

        $Supplier->name       = $request->get('name');
        $Supplier->address    = $request->get('address');
        $Supplier->phone1      = $request->get('phone1');
        $Supplier->phone2      = $request->get('phone2');
        $Supplier->mobile1      = $request->get('mobile1');
        $Supplier->mobile2      = $request->get('mobile2');

        if($Supplier->save()):
             $notification = array(
                'message' => 'Successfully updated Supplier',
                'alert-type' => 'success'
            ); 
            return redirect('Supplier')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Supplier',
                    'alert-type' => 'error'
                );
            return redirect('Supplier')->with($notification);
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
        $Supplier = Supplier::find($id);
        if($Supplier->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Supplier',
                'alert-type' => 'success'
            ); 
            return redirect('Supplier')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Supplier',
                    'alert-type' => 'error'
                );
            return redirect('Supplier')->with($notification);
        endif;
    }
}
