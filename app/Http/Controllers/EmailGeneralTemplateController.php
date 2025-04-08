<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\EmailGeneralTemplate;

class EmailGeneralTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = EmailGeneralTemplate::latest()->get();
        return view('EmailGeneralTemplate.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('EmailGeneralTemplate.create');
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

        $template = new EmailGeneralTemplate([
            'name'       => $request->get('name'),
            'template'   => $request->get('template'),
        ]);

        if($template->save()):
             $notification = array(
                'message' => 'Successfully added Template',
                'alert-type' => 'success'
            ); 
            return redirect('EmailGeneralTemplate')->with($notification);
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
        $template = EmailGeneralTemplate::find($id);
        return view('EmailGeneralTemplate.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = EmailGeneralTemplate::find($id);
        return view('EmailGeneralTemplate.update', compact('template'));
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

        $template = EmailGeneralTemplate::find($id);
        
        $template->name       = $request->get('name');
        $template->template   = $request->get('template');

        if($template->save()):
             $notification = array(
                'message' => 'Successfully updated Template',
                'alert-type' => 'success'
            ); 
            return redirect('EmailGeneralTemplate')->with($notification);
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
        $template = EmailGeneralTemplate::find($id);
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
