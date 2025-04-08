<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Categories = ExpenseCategory::latest()->get();
        return view('ExpenseCategory.index', compact('Categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ExpenseCategory.create');
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
            'category'          => 'required|unique:expense_categories',
        ]);

        $ExpenseCategory = new ExpenseCategory([
            'category' => $request->get('category'),
        ]);

        if($ExpenseCategory->save()):
             $notification = array(
                'message' => 'Successfully added Category',
                'alert-type' => 'success'
            ); 
            return redirect('ExpenseCategory')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Category',
                    'alert-type' => 'error'
                );
            return redirect('ExpenseCategory')->with($notification);
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
        $category  = ExpenseCategory::find($id);
        return view('ExpenseCategory.update', compact('category'));
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
            'category'          => 'required|unique:expense_categories',
        ]);

        $ExpenseCategory = ExpenseCategory::find($id);
        $ExpenseCategory->category = $request->get('category');

        if($ExpenseCategory->save()):
             $notification = array(
                'message' => 'Successfully updated Category',
                'alert-type' => 'success'
            ); 
            return redirect('ExpenseCategory')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to updated Category',
                    'alert-type' => 'error'
                );
            return redirect('ExpenseCategory')->with($notification);
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
        $ExpenseCategory = ExpenseCategory::find($id);
        if($ExpenseCategory->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Expense Category',
                'alert-type' => 'success'
            ); 
            return redirect('ExpenseCategory')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Expense Category',
                    'alert-type' => 'error'
                );
            return redirect('ExpenseCategory')->with($notification);
        endif;
    }
}
