<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Salary;
use Auth;
use App\Models\Employee;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortOrder = $request->input('sort', 'asc'); // Get the sort order from the request (default is 'asc')
        
        // Fetch the salary data from the database and sort by the 'months' column
        
        $Salaries = Salary::orderBy('month', $sortOrder)->get();
        return view('Salary.index', compact('Salaries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Staffs = Employee::latest()->get();
        return view('Salary.create', compact('Staffs'));
    }

    public function fillSalary($id)
    {
        $staff = Employee::find($id);
        return view('Salary.fillSalary', compact('staff'));
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
            'month'         => 'required',
            'salary'        => 'required',
            'over_time'     => 'required',
            'night_half'     => 'required',
            'night_full'         => 'required',
            'dns_allounce'         => 'required',
            'house_rent'         => 'required',
            'convence'         => 'required',
            'advance'       => 'required',
            'absent_days'   => 'required',
            'allow_leave'   => 'required',
            'half_day'      => 'required',
            'ensurance' => 'required',
            'provident' => 'required',
            'professional' => 'required',
            'tax' => 'required',
            'prepared_by'   => 'required',
        ]);
        
        $total_earnings = 0;
        $total_earnings += $request->salary;
        $total_earnings += $request->advance;
        $total_earnings += $request->absent_days*$request->absent_amount;
        $total_earnings += $request->night;
        $total_earnings += $request->bike_maintenance;
        
        $total_deduction = 0;
        $total_deduction += $request->advance;
        $total_deduction += $request->absent_days;
        $total_deduction += $request->absent_amount;
        $total_deduction += $request->half_day;
        
        $net_salary = $total_earnings-$total_deduction;

        $Salary = new Salary([
            'user_id'       => $request->get('user_id'),
            'month'         => $request->get('month'),
            'salary'        => $request->get('salary'),
            'over_time'     => $request->get('over_time'),
            'night_half'     => $request->get('night_half'),
            'night_full'         => $request->get('night_full'),
            'dns_allounce'         => $request->get('dns_allounce'),
            'house_rent'         => $request->get('house_rent'),
            'convence'         => $request->get('convence'),
            'advance'       => $request->get('advance'),
            'absent_days'   => $request->get('absent_days'),
            'absent_amount'   => $request->get('allow_leave'),
            'half_day'      => $request->get('half_day'),
            'ensurance'      => $request->get('ensurance'),
            'provident'      => $request->get('provident'),
            'professional'      => $request->get('professional'),
            'tax'      => $request->get('tax'),
            'prepared_by'    => $request->get('prepared_by'),
            'total_deduction'=> $total_deduction,
            'gross_earning' => $total_earnings,
            'net_salary'  => $net_salary,
            'dated'          => date('Y-m-d'),
        ]);

        if($Salary->save()):
             $notification = array(
                'message' => 'Successfully added Salary',
                'alert-type' => 'success'
            ); 
            return redirect('Salary')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Salary',
                    'alert-type' => 'error'
                );
            return redirect('Salary')->with($notification);
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
        $Salary  = Salary::find($id);
        $staff = Employee::find($Salary->user_id);
        return view('Salary.show', compact('Salary', 'staff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Salary  = Salary::find($id);
        $staff = Employee::find($Salary->user_id);
        return view('Salary.update', compact('Salary', 'staff'));
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
            'month'         => 'required',
            'salary'        => 'required',
            'over_time'     => 'required',
            'night_half'     => 'required',
            'night_full'         => 'required',
            'dns_allounce'         => 'required',
            'medical_allounce'         => 'required',
            'house_rent'         => 'required',
            'convence'         => 'required',
            'advance'       => 'required',
            'absent_days'   => 'required',
            'allow_leave'   => 'required',
            'half_day'      => 'required',
            'ensurance' => 'required',
            'provident' => 'required',
            'professional' => 'required',
            'tax' => 'required',
            'prepared_by'   => 'required',
        ]);
        
        $total_earnings = 0;
        $total_earnings += $request->salary;
        $total_earnings += $request->advance;
        $total_earnings += $request->absent_days*$request->absent_amount;
        $total_earnings += $request->night;
        $total_earnings += $request->bike_maintenance;
        
        $total_deduction = 0;
        $total_deduction += $request->advance;
        $total_deduction += $request->absent_days;
        $total_deduction += $request->absent_amount;
        $total_deduction += $request->half_day;
        
        $net_salary = $total_earnings-$total_deduction;

        $Salary  = Salary::find($id);
        
    $Salary->user_id = $request->get('user_id');
    $Salary->month = $request->get('month');
    $Salary->salary = $request->get('salary');
    $Salary->over_time = $request->get('over_time');
    $Salary->night_half = $request->get('night_half');
    $Salary->night_full = $request->get('night_full');
    $Salary->dns_allounce = $request->get('dns_allounce');
    $Salary->medical_allounce = $request->get('medical_allounce');
    $Salary->house_rent = $request->get('house_rent');
    $Salary->convence = $request->get('convence');
    $Salary->advance = $request->get('advance');
    $Salary->absent_days = $request->get('absent_days');
    $Salary->absent_amount = $request->get('allow_leave');
    $Salary->half_day = $request->get('half_day');
    $Salary->ensurance = $request->get('ensurance');
    $Salary->provident = $request->get('provident');
    $Salary->professional = $request->get('professional');
    $Salary->tax = $request->get('tax');
    $Salary->prepared_by = $request->get('prepared_by');
    $Salary->total_deduction = $total_deduction;
    $Salary->gross_earning  = $total_earnings;
    $Salary->net_salary  = $net_salary;

        if($Salary->save()):
             $notification = array(
                'message' => 'Successfully Edit Salary',
                'alert-type' => 'success'
            ); 
            return redirect('Salary')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Salary',
                    'alert-type' => 'error'
                );
            return redirect('Salary')->with($notification);
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
        $Salary = Salary::find($id);
        if($Salary->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Salary',
                'alert-type' => 'success'
            ); 
            return redirect('Salary')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Salary',
                    'alert-type' => 'error'
                );
            return redirect('Salary')->with($notification);
        endif;
    }
}
