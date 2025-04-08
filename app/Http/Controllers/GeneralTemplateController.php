<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralTemplate;

class GeneralTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = GeneralTemplate::latest()->get();
        return view('GeneralTemplate.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('GeneralTemplate.create');
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Htt p\Response
     */
    public function store(Request $request)
    {
        $request->validate([ 
            'name'        => 'required', 
            'template'    => 'required',
        ]);

        $template = new GeneralTemplate([
            'name'       => $request->get('name'),
            'template'   => $request->get('template'),
        ]);

        if($template->save()):
             $notification = array(
                'message' => 'Successfully added Template',
                'alert-type' => 'success'
            ); 
            return redirect('GeneralTemplate')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add Template',
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
        $template = GeneralTemplate::find($id);
        return view('GeneralTemplate.show', compact('template'));
    }

    public function general_template_with_header($id)
    {
        $template = GeneralTemplate::find($id);
        return view('GeneralTemplate.general_template_with_header', compact('template'));
    }

    public function general_template_without_header($id)
    {
        $template = GeneralTemplate::find($id);
        return view('GeneralTemplate.general_template_without_header', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = GeneralTemplate::find($id);
        return view('GeneralTemplate.update', compact('template'));
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
            'name'        => 'required', 
            'template'    => 'required',
        ]);

        $template = GeneralTemplate::find($id);
        
        $template->name       = $request->get('name');
        $template->template   = $request->get('template');

        if($template->save()):
             $notification = array(
                'message' => 'Successfully updated Template',
                'alert-type' => 'success'
            ); 
            return redirect('GeneralTemplate')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update Template',
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
        $template = GeneralTemplate::find($id);
        if($template->delete()):
             $notification = array(
                'message' => 'Successfully Deleted Template',
                'alert-type' => 'success'
            ); 
            return back()->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete Template',
                    'alert-type' => 'error'
                );
            return back()->with($notification);
        endif;
    }
}
