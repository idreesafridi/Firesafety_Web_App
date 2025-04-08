<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Branches = Branch::latest()->get();
        return view('Branch.index', compact('Branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Branch.create');
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
            'branch_name'       => 'required',
            'branch_address'    => 'required',
            'manager_name'      => 'required',
            'phone_number'      => 'required|unique:branches',
        ]);

        $Branch = new Branch([
            'branch_name'       => $request->get('branch_name'),
            'branch_address'    => $request->get('branch_address'),
            'manager_name'      => $request->get('manager_name'),
            'phone_number'      => $request->get('phone_number'),
        ]);

        if($Branch->save()):
             $notification = array(
                'message' => 'Successfully added Branch',
                'alert-type' => 'success'
            ); 
            return redirect('Branch')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Branch',
                    'alert-type' => 'error'
                );
            return redirect('Branch')->with($notification);
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
        $Branch = Branch::find($id);
        return view('Branch.update', compact('Branch'));
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
            'branch_name'       => 'required',
            'branch_address'    => 'required',
            'manager_name'      => 'required',
            'phone_number'      => 'required',
        ]);

        $Branch = Branch::find($id);

        $Branch->branch_name       = $request->get('branch_name');
        $Branch->branch_address    = $request->get('branch_address');
        $Branch->manager_name      = $request->get('manager_name');
        $Branch->phone_number      = $request->get('phone_number');

        if($Branch->save()):
             $notification = array(
                'message' => 'Successfully updated Branch',
                'alert-type' => 'success'
            ); 
            return redirect('Branch')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Branch',
                    'alert-type' => 'error'
                );
            return redirect('Branch')->with($notification);
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
        $Branch = Branch::find($id);
        if($Branch->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Branch',
                'alert-type' => 'success'
            ); 
            return redirect('Branch')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Branch',
                    'alert-type' => 'error'
                );
            return redirect('Branch')->with($notification);
        endif;
    }
}
