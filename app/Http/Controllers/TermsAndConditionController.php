<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TermsAndConditions;
 
class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $TermsAndConditions = TermsAndConditions::latest()->get();
        return view('TermsAndConditions.index', compact('TermsAndConditions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('TermsAndConditions.create');
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
            'name' => 'required',
            'termsAndConditions' => 'required',
        ]);

        
        $TermsAndConditions = new TermsAndConditions();

        $TermsAndConditions->name     = $request->get('name');
        $TermsAndConditions->termsAndConditions     = $request->get('termsAndConditions');

        if($TermsAndConditions->save()):
             $notification = array(
                'message' => 'Successfully updated Terms & Conditions',
                'alert-type' => 'success'
            ); 
            return redirect('TermsAndCondition')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Terms & Conditions',
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
        $TermsAndConditions = TermsAndConditions::find($id);
        return view('TermsAndConditions.update', compact('TermsAndConditions'));
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
            'termsAndConditions'       => 'required',
        ]);

        
        $TermsAndConditions = TermsAndConditions::find($id);

        $TermsAndConditions->name     = $request->get('name');
        $TermsAndConditions->termsAndConditions     = $request->get('termsAndConditions');

        if($TermsAndConditions->save()):
             $notification = array(
                'message' => 'Successfully updated Terms & Conditions',
                'alert-type' => 'success'
            ); 
            return redirect('TermsAndCondition')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Terms & Conditions',
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
        $TermsAndConditions = TermsAndConditions::find($id);
        if($TermsAndConditions->delete()):
             $notification = array(
                'message' => 'Successfully deleted Terms & Conditions',
                'alert-type' => 'success'
            ); 
            return back()->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to deleted Terms & Conditions',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }
}
