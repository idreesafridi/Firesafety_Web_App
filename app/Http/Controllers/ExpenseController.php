<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Expense;
use App\Models\Branch;
use Auth;
use App\Models\ExpenseCategory;
use App\Models\ExpensetoCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

        public function index(Request $request)
        {
            $user = auth()->user();
            $branches = Branch::latest()->get();

            if ($user->designation === 'Super Admin') {
                $Expenses = Expense::latest()->get();
            } else {
                $Expenses = Expense::where('branch_id', $user->branch_id)->latest()->get();
            }

            \DB::enableQueryLog();

            return view('Expense.index', compact('Expenses', 'branches'));
        }

    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ExpenseCategory::latest()->get();
        $branches   = Branch::latest()->get();
        $user = auth()->user();

        
        return view('Expense.create', compact('categories', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
     public function store(Request $request)
     {
         // Validate the incoming request data
         $request->validate([
             'branch_id' => 'required|exists:branches,id',
             'dated' => 'required|date',
             //'category_id.*' => 'required|exists:categories,id',
             'qty.*' => 'required|string|min:0',
             'price.*' => 'required|numeric|min:0',
             'cashreceived' => 'required|numeric|min:0',
             'pbalance' => 'required|numeric|min:0',
             'amount' => 'required|numeric|min:0',
             'cashinhand' => 'required|numeric|min:0',
             'remainingbalance' => 'required|numeric|min:0',
         ]);
     
         $existingExpense = Expense::where('dated', $request->input('dated'))->exists();

         // If an expense with the same attributes already exists, redirect back with message
         if ($existingExpense) {
             return redirect()->back()->with('error', 'An expense with the same date already exists.');
         }
         
                

         // Create a new Expense instance
         $expense = new Expense();
         $expense->user_id = $request->user()->id; // Access authenticated user directly
         $expense->branch_id = $request->input('branch_id');
         $expense->dated = $request->input('dated');
         $expense->cashreceived = $request->input('cashreceived');
         $expense->pbalance = $request->input('pbalance');
         $expense->amount = $request->input('amount');
         $expense->cashinhand = $request->input('cashinhand');
         $expense->remainingbalance = $request->input('remainingbalance');
     
         // Save the main expense details
      

         if($expense->save()): 
            // InvoiceCategories
            $categoryIdsArray    = $request->category_id;
            $qtyArray           = $request->qty;
            $priceArray          = $request->price;
            // Add other arrays as needed
        
            if(isset($categoryIdsArray[0])) 
                $count = 0;
                foreach($categoryIdsArray as $categoryId):
                    $ExpenseCategories = new ExpensetoCategory([
                        'expense_id'    => $expense->id,
                        'category_id'   => $categoryId,
                        'qty'           => $qtyArray[$count],
                        'price'         => $priceArray[$count],
                        // Add other fields as needed
                    ]);
        
                    $ExpenseCategories->save();
                    $count++;
                endforeach;
            endif;
            $expense->save();
                
            
          
            return redirect()->route('expenses')->with('success', 'Expense added successfully.');

       
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($date)
{
    $user = Auth::user()->get();
    // Assuming $request is available
    $from_month = request()->from_month ?? date('Y-m');
    $to_month = request()->to_month ?? date('Y-m');

    $from_date = $from_month . '-01';
    $to_date = date('Y-m-t', strtotime($to_month));

    $year = date('Y');
    $prev_year = date("Y", strtotime("-1 year"));

    // Retrieve the expenses based on the date range
    $Expenses = Expense::whereBetween('dated', [$from_date, $to_date])
                        ->where('dated', $date)
                        ->get();

    $branches = Branch::latest()->get();
    $arrPrevExpenses = [];

    foreach ($branches as $objBranch) {
        $branch_id = $objBranch->id;
        $previous_expense_amount = Expense::whereDate('dated', '<', $from_date)
                                          ->where('branch_id', $branch_id)
                                          ->sum('amount');
        $previous_receipt_amount = Expense::whereDate('dated', '<', $from_date)
                                          ->where('branch_id', $branch_id)
                                          ->sum('cashreceived');
        $arrprevbalance = $previous_receipt_amount - $previous_expense_amount;
        $arrPrevExpenses[$branch_id]['pbalance'] = $arrprevbalance;
    }

    // Ensure there's at least one expense
    if ($Expenses->isEmpty()) {
        // Handle the case where no expense is found, maybe redirect or show a message
        abort(404, 'Expense not found');
    }

    $expense = $Expenses->first(); // Get the first expense

    // Retrieve the categories for the specific expense
    $ExpenseCategories = ExpensetoCategory::where('expense_id', $expense->id)->get();
    
    return view('Expense.show', compact('Expenses', 'from_month', 'to_month', 'year', 'branches', 'arrPrevExpenses', 'date', 'ExpenseCategories' ,'user'));
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Expense  = Expense::find($id);
        $branches   = Branch::latest()->get();
        $categories = ExpenseCategory::latest()->get();
        $ExpensetoCategories = ExpensetoCategory::latest()->get();

       

        return view('Expense.update', compact('Expense', 'branches', 'categories','ExpensetoCategories'));
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
        // Validate the incoming request data
$request->validate([
    'branch_id' => 'required|exists:branches,id',
    'dated' => 'required|date',
    //'category_id.*' => 'required|exists:categories,id',
    'qty.*' => 'required|string|min:0',
    'price.*' => 'required|numeric|min:0',
    'cashreceived' => 'required|numeric|min:0',
    'pbalance' => 'required|numeric|min:0',
    'amount' => 'required|numeric|min:0',
    'cashinhand' => 'required|numeric|min:0',
    'remainingbalance' => 'required|numeric|min:0',
]);

// Find the expense to update
$expense = Expense::findOrFail($id);
$expense->branch_id = $request->input('branch_id');
$expense->dated = $request->input('dated');
$expense->cashreceived = $request->input('cashreceived');
$expense->pbalance = $request->input('pbalance');
$expense->amount = $request->input('amount');
$expense->cashinhand = $request->input('cashinhand');
$expense->remainingbalance = $request->input('remainingbalance');

// Save the main expense details
if ($expense->save()) {
    // InvoiceCategories
    $categoryIdsArray = $request->category_id;
    $qtyArray = $request->qty;
    $priceArray = $request->price;

    if (is_array($categoryIdsArray)) {
        // Loop through each category
        foreach ($categoryIdsArray as $key => $categoryId) {
            // Check if this category already exists for this expense
            $existingCategory = ExpensetoCategory::where('expense_id', $expense->id)
                ->where('category_id', $categoryId)
                ->first();

            if ($existingCategory) {
                // Update existing category
                $existingCategory->qty = $qtyArray[$key];
                $existingCategory->price = $priceArray[$key];
                // Add other fields as needed
                $existingCategory->save();
            } else {
                // Add new category
                $newCategory = new ExpensetoCategory([
                    'expense_id' => $expense->id,
                    'category_id' => $categoryId,
                    'qty' => $qtyArray[$key],
                    'price' => $priceArray[$key],
                    // Add other fields as needed
                ]);
                $newCategory->save();
            }
        }
    }

    $notification = [
        'message' => 'Successfully updated Invoice',
        'alert-type' => 'success'
    ];
} else {
    $notification = [
        'message' => 'Failed to update Invoice',
        'alert-type' => 'error'
    ];
}

if (Auth::user()->designation == 'Super Admin' || Auth::user()->designation == 'Branch Admin') {
    return redirect('expenses')->with($notification);
} else {
    return redirect('/')->with($notification);
}

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Expense = Expense::find($id);
        if($Expense->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Expense',
                'alert-type' => 'success'
            ); 
            return redirect('Expenses')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Expense',
                    'alert-type' => 'error'
                );
            return redirect('Expenses')->with($notification);
        endif;
    }
/*
    public function expensesdaterange(Request $request) // NOT USING
        $from_month = $request->from ?? date('Y-m');
        $to_month = $request->to ?? date('Y-m');
    
        $from_date = $from_month . '-01';
        $to_date = date('Y-m-t', strtotime($to_month));
        

//        $from_month = date('Y-m');
  //      $to_month = '';
        $year = date('Y');
        $prev_year = date("Y",strtotime("-1 year"));


        // Expired Invoicees
        
        $Expenses = Expense::whereBetween('dated', [$from_date, $to_date])->latest()->get();
        $branches   = Branch::latest()->get();
        //$prev_amount = 0;
        $prev_amount = Expense::where('dated','<', $from_date )->get();

        $type = 'Current Month';
        return view('Expense.index', compact( 'from_month', 'to_month', 'year', 'Expenses','branches'));
        
    } */

    public function expensereportshow($branch_id ,Request $request)
{
    $from_month = $request->from_month ?? date('Y-m');
    $to_month = $request->to_month?? date('Y-m');

    $from_date = $from_month . '-01';
    $to_date = date('Y-m-t', strtotime($to_month));
    

//        $from_month = date('Y-m');
//      $to_month = '';
    $year = date('Y');
    $prev_year = date("Y",strtotime("-1 year"));
    
   
    $Expenses = Expense::whereBetween('dated', [$from_date, $to_date])->where('branch_id', $branch_id)->orderBy('dated')->pluck('dated')->unique();
    $expensesByDate = Expense::whereBetween('dated', [$from_date, $to_date])->where('branch_id', $branch_id)->orderBy('dated')->get()->groupBy('dated');
    
    //$Expenses = Expense::whereBetween('dated', [$from_date, $to_date])->latest()->get();

    return view('Expense.branchshow', compact('Expenses','from_month', 'to_month', 'year','expensesByDate' ));
}


public function allexpenseshow(Request $request)
{
    $from_month = $request->from_month ?? date('Y-m');
    $to_month = $request->to_month?? date('Y-m');

    $from_date = $from_month . '-01';
    $to_date = date('Y-m-t', strtotime($to_month));
    

//        $from_month = date('Y-m');
//      $to_month = '';
    $year = date('Y');
    $prev_year = date("Y",strtotime("-1 year"));
   
    $Expenses = Expense::whereBetween('dated', [$from_date, $to_date])->get();
    $branches   = Branch::latest()->get();
    $arrPrevExpenses = [];
    foreach ($branches as $objBranch)
    {
        $branch_id = $objBranch->id;
        $previous_expense_amount = Expense::whereDate('dated','<', $from_date )->where('branch_id', $branch_id)->sum('amount');
        $previous_receipt_amount = Expense::whereDate('dated','<', $from_date )->where('branch_id', $branch_id)->sum('cash_received');
        
        $arrprevbalance = $previous_receipt_amount - $previous_expense_amount;
        $arrPrevExpenses[$branch_id]['prev_balance'] = $arrprevbalance;
    }

    //$Expenses = Expense::whereBetween('dated', [$from_date, $to_date])->latest()->get();

    return view('Expense.allshow', compact('Expenses','from_month', 'to_month', 'year','branches', 'arrPrevExpenses' ));
}

public function downloadExpenses(Request $request)
{
    $year = $request->input('year');

    if ($year) {
        $expenses = Expense::whereYear('dated', $year)->get();
        $filename = 'expenses_' . $year . '.csv';
    } else {
        $expenses = Expense::all();
        $filename = 'all_expenses.csv';
    }

    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=" . $filename,
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

    $handle = fopen('php://output', 'w');

    fputcsv($handle, [
        'Date',
        'Previous Balance',
        'Cash Received',
        'Current in Hand',
        'Expense',
        'Remaining Balance'
    ]);

    foreach ($expenses as $expense) {
        fputcsv($handle, [
            $expense->dated,
            $expense->pbalance,
            $expense->cashreceived,
            $expense->cashinhand,
            $expense->amount,
            $expense->remainingbalance
        ]);
    }

    fclose($handle);

    return Response::make('', 200, $headers);
}

}
