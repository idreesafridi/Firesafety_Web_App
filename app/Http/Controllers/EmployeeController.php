<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branch;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Employees = Employee::latest()->get();
        return view('Employees.index', compact('Employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Branches = Branch::latest()->get();
        return view('Employees.create', compact('Branches'));
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
            'name'      => 'required',
            //'phone'     => 'unique:employees',
            // 'address'   => 'required',
           // 'email'      => 'required|unique:employees',
            // 'salary'      => 'required',
            // 'bank'      => 'required',
            // 'account_no'      => 'required',
           // 'branch'      => 'required',
        ]);

        $Employee = new Employee([
            'name'      => $request->get('name'),
            'phone'     => $request->get('phone'),
            'address'   => $request->get('address'),
            'type'      => $request->get('type'),
            'email'      => $request->get('email'),
            'salary'      => $request->get('salary'),
            'bank'      => $request->get('bank'),
            'account_no'      => $request->get('account_no'),
            'branch'      => $request->get('branch'),
            'bike_maintenance'      => $request->get('bike_maintenance'),
            
        ]);

        if($Employee->save()):
             $notification = array(
                'message' => 'Successfully added Employee',
                'alert-type' => 'success'
            ); 
            return redirect('Employees')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Employee',
                    'alert-type' => 'error'
                );
            return redirect('Employees')->with($notification);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $Branches = Branch::latest()->get();
        $Employee = Employee::find($id);
        return view('Employees.update', compact('Employee', 'Branches'));
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
            'name'      => 'required',
            // 'address'   => 'required',
           // 'email'      => 'required',
            // 'salary'      => 'required',
            // 'bank'      => 'required',
            // 'account_no'      => 'required',
            // 'branch'      => 'required',
        ]);

        $Employee = Employee::find($id);
        $Employee->name      = $request->get('name');
        $Employee->phone     = $request->get('phone');
        $Employee->address   = $request->get('address');
        $Employee->type      = $request->get('type');
        $Employee->email      = $request->get('email');
        $Employee->salary      = $request->get('salary');
        $Employee->bank      = $request->get('bank');
        $Employee->account_no      = $request->get('account_no');
        $Employee->branch      = $request->get('branch');
        $Employee->bike_maintenance      = $request->get('bike_maintenance');

        if($Employee->save()):
             $notification = array(
                'message' => 'Successfully updated Employee',
                'alert-type' => 'success'
            ); 
            return redirect('Employees')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Employee',
                    'alert-type' => 'error'
                );
            return redirect('Employees')->with($notification);
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
        $Employee = Employee::find($id);
        if($Employee->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Employee',
                'alert-type' => 'success'
            ); 
            return redirect('Employees')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Employee',
                    'alert-type' => 'error'
                );
            return redirect('Employees')->with($notification);
        endif;
    }
}
