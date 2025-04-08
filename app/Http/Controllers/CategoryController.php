<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Categories = Category::latest()->get();
        return view('Category.index', compact('Categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Category.create');
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
            'name'       => 'required|unique:categories',
        ]);

        $Category = new Category([
            'name'       => $request->get('name'),
        ]);

        if($Category->save()):
             $notification = array(
                'message' => 'Successfully added Category',
                'alert-type' => 'success'
            ); 
            return redirect('Category')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Category',
                    'alert-type' => 'error'
                );
            return redirect('Category')->with($notification);
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
        $Category = Category::find($id);
        return view('Category.update', compact('Category'));
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
            'name'       => 'required|unique:categories,name,'.$id,
        ]);

        $Category = Category::find($id);

        $Category->name       = $request->get('name');

        if($Category->save()):
             $notification = array(
                'message' => 'Successfully updated Category',
                'alert-type' => 'success'
            ); 
            return redirect('Category')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Category',
                    'alert-type' => 'error'
                );
            return redirect('Category')->with($notification);
        endif;
    }

    public function update_expire_invoice(Request $request, $id)
    {
        $request->validate([
            'expire_invoice'       => 'required',
        ]);
        $Category = Category::find($id);
        $Category->expire_invoice = $request->get('expire_invoice');
        if($Category->save()):
             $notification = array(
                'message' => 'Successfully updated Category',
                'alert-type' => 'success'
            ); 
            return redirect('Category')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Category',
                    'alert-type' => 'error'
                );
            return redirect('Category')->with($notification);
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
        $Category = Category::find($id);
        if($Category->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Category',
                'alert-type' => 'success'
            ); 
            return redirect('Category')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Category',
                    'alert-type' => 'error'
                );
            return redirect('Category')->with($notification);
        endif;
    }
}
