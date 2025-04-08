<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Products = Product::latest()->get();
        return view('Products.index', compact('Products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Categories = Category::latest()->get();
        $Suppliers  = Supplier::latest()->get();
        
        $lastCount = Product::count();
        
        if($lastCount>0):
            $lastProduct = Product::latest()->first();
            $lastCode = $lastProduct->code;
            $newcode = $lastCode+1;
        else:
            $newcode = 1001;
        endif;

        return view('Products.create', compact('Categories', 'Suppliers', 'newcode'));
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
            'code'              => 'required',
            'supplier_id'       => 'required',
            'name'              => 'required',
            'category_id'       => 'required',
            // 'buying_price_per_unit'     => 'required',
            // 'selling_price_per_unit'    => 'required',
            'unit'              => 'required',
            'description'       => 'required',
            'dated'             => 'required',
            'inventory'         => 'required',
        ]);

        $fileNameToStore = '';
        if($request->hasFile('image')):
            $file = $request->file('image');
            // Get filename with extension            
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/Product/';
            $file->move($path, $fileNameToStore);
        endif;
        
        $capacity               =   $request->get('capacity');
        $buying_price_per_unit  =   $request->get('buying_price_per_unit');
        $selling_price_per_unit =   $request->get('selling_price_per_unit');
        
        $capacityStr                = implode(', ', $capacity);
        $buying_price_per_unitStr   = implode(', ', $buying_price_per_unit);
        $selling_price_per_unitStr  = implode(', ', $selling_price_per_unit);

        $Product = new Product([
            'code'              => $request->get('code'),
            'supplier_id'       => $request->get('supplier_id'),
            'name'              => $request->get('name'),
            'category_id'       => $request->get('category_id'),
            'capacity'                  => $capacityStr,
            'buying_price_per_unit'     => $buying_price_per_unitStr,
            'selling_price_per_unit'    => $selling_price_per_unitStr,
            'unit'              => $request->get('unit'),
            'image'             => $fileNameToStore,
            'description'       => $request->get('description'),
            'dated'       => $request->get('dated'),
            'expiry_date' => $request->get('expiry_date'),
            'inventory' => $request->get('inventory'),
        ]);

        if($Product->save()):
             $notification = array(
                'message' => 'Successfully added Product',
                'alert-type' => 'success'
            ); 
            return redirect('Products')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Product',
                    'alert-type' => 'error'
                );
            return redirect('Products')->with($notification);
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
        $Product = Product::find($id);
        return view('Products.show', compact('Product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Product = Product::find($id);
        $Categories = Category::latest()->get();
        $Suppliers  = Supplier::latest()->get();
        return view('Products.update', compact('Product', 'Categories', 'Suppliers'));
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
            'code'              => 'required',
            'supplier_id'       => 'required',
            'name'              => 'required',
            'category_id'       => 'required',
            'buying_price_per_unit'     => 'required',
            'selling_price_per_unit'    => 'required',
            'unit'              => 'required',
            'description'       => 'required',
            'dated'             => 'required',
            'inventory'         => 'required',
        ]);

        $Product = Product::find($id);

        if($request->hasFile('image')):
            $file = $request->file('image');
            // Get filename with extension            
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/Product/';
            $file->move($path, $fileNameToStore);
        else:
            $fileNameToStore = $Product->image;
        endif;
        
        $capacity               =   $request->get('capacity');
        $buying_price_per_unit  =   $request->get('buying_price_per_unit');
        $selling_price_per_unit =   $request->get('selling_price_per_unit');
        
        $capacityStr                = implode(', ', $capacity);
        $buying_price_per_unitStr   = implode(', ', $buying_price_per_unit);
        $selling_price_per_unitStr  = implode(', ', $selling_price_per_unit);

        $Product->code                      = $request->get('code');
        $Product->supplier_id               = $request->get('supplier_id');
        $Product->name                      = $request->get('name');
        $Product->category_id               = $request->get('category_id');
        $Product->capacity                  = $capacityStr;
        $Product->buying_price_per_unit     = $buying_price_per_unitStr;
        $Product->selling_price_per_unit    = $selling_price_per_unitStr;
        $Product->unit                      = $request->get('unit');
        $Product->description               = $request->get('description');
        $Product->image                     = $fileNameToStore;
        $Product->dated                     = $request->get('dated');
        $Product->expiry_date               = $request->get('expiry_date');
        $Product->inventory               = $request->get('inventory');

        if($Product->save()):
             $notification = array(
                'message' => 'Successfully updated Product',
                'alert-type' => 'success'
            ); 
            return redirect('Products')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Product',
                    'alert-type' => 'error'
                );
            return redirect('Products')->with($notification);
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
        $Product = Product::find($id);
        if($Product->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Product',
                'alert-type' => 'success'
            ); 
            return redirect('Products')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Product',
                    'alert-type' => 'error'
                );
            return redirect('Products')->with($notification);
        endif;
    }
    
    public function updateInventory(Request $request)
    {
        $Product = Product::find($request->product_id);
        $Product->inventory = $request->inventory;
        if($Product->save()):
             $notification = array(
                'message' => 'successfully updated inventory.',
                'alert-type' => 'success'
            ); 
            return redirect()->back()->with($notification);
        else:
            $notification = array(
                    'message' => 'something went wrong. please try again.',
                    'alert-type' => 'error'
                );
            return redirect()->back()->with($notification);
        endif;
    }
}
