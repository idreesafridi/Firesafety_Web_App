<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;
use App\Models\Branch;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Users = User::latest()->get();
        return view('User.index', compact('Users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Branches = Branch::latest()->get();
        return view('User.create', compact('Branches'));
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
            'username'      => 'required',
            'email'         => 'required|unique:users',
            'password'      => 'required|confirmed|min:8',
            'branch'        => 'required',
            'designation'   => 'required',
            'custom_designation'=> 'required',
        ]);

        if($request->hasFile('signature')):
            $file = $request->file('signature');
            // Get filename with extension            
            $filenameWithExt = $request->file('signature')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('signature')->getClientOriginalExtension();
            //Filename to store
            $signature = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/signature/';
            $file->move($path, $signature);
        endif;

        
        if(!empty($request->rights)):
            $rights = implode(', ', $request->rights);
        else:
            $rights = '';
        endif;

        $branch = Branch::where('branch_name', $request->branch)->first();
        
        
        $User = new User([
            'username'          => $request->get('username'),
            'email'             => $request->get('email'),
            'password'          => Hash::make($request->get('password')),
            'phone_number'      => $request->get('phone_number'),
            'address'           => $request->get('address'),
            //'branch'            => $request->get('branch'),
            'designation'       => $request->get('designation'),
            'custom_designation'       => $request->get('custom_designation'),
            'salary'            => $request->get('salary'),
            'bank'              => $request->get('bank'),
            'account_no'        => $request->get('account_no'),
            'rights'            => $rights,
            'signature'         => $signature,
            'branch_id'         => $branch->id,
        ]);

        if($User->save()):
             $notification = array(
                'message' => 'Successfully added User',
                'alert-type' => 'success'
            ); 
            return redirect('User')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to add User',
                    'alert-type' => 'error'
                );
            return redirect('User')->with($notification);
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
        $User = User::find($id);
        $Branches = Branch::latest()->get();
        return view('User.update', compact('User', 'Branches'));
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
            'username'      => 'required',
            'email'         => 'required',
            'branch'        => 'required',
            'designation'   => 'required',
            'custom_designation'=> 'required',
        ]);

        $User = User::find($id);

        if($request->hasFile('signature')):
            $file = $request->file('signature');
            // Get filename with extension            
            $filenameWithExt = $request->file('signature')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('signature')->getClientOriginalExtension();
            //Filename to store
            $signature = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/signature/';
            $file->move($path, $signature);
        else:
            $signature = $User->signature;
        endif;

        if(!empty($request->rights)):
            $rights = implode(', ', $request->rights);
        else:
            $rights = $User->rights;
        endif;

        $branch = Branch::where('branch_name', $request->branch)->first();

        $User->username          = $request->get('username');
        $User->email             = $request->get('email');
        $User->phone_number      = $request->get('phone_number');
        $User->address           = $request->get('address');
        $User->branch            = $request->get('branch');
        $User->designation       = $request->get('designation');
        $User->custom_designation= $request->get('custom_designation');
        $User->salary            = $request->get('salary');
        $User->bank              = $request->get('bank');
        $User->account_no        = $request->get('account_no');
        $User->rights            = $rights;
        $User->signature         = $signature;
        $User->branch_id         = $branch->id;

        if($User->save()):
             $notification = array(
                'message' => 'Successfully updated User',
                'alert-type' => 'success'
            ); 
            return redirect('User')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to update User',
                    'alert-type' => 'error'
                );
            return redirect('User')->with($notification);
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
        $User = User::find($id);
        if($User->delete()):
             $notification = array(
                'message' => 'Successfully Deleted User',
                'alert-type' => 'success'
            ); 
            return redirect('User')->with($notification);
        else:
            $notification = array(
                    'message' => 'Failed to delete User',
                    'alert-type' => 'error'
                );
            return redirect('User')->with($notification);
        endif;
    }

    public function changePassword()
    {
        return view('User/changePassword');
    }
    public function updatePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success","Password changed successfully !");
    }

    public function Profile()
    {
        return view('User/Profile');
    }

    public function changeProfile(Request $request)
    {
        $validatedData = $request->validate([
            'username'      => 'required',
            'address'       => 'required',
            'email'         => 'required',
            'phone_number'  => 'required',
            'Tel'  => 'required',
        ]);

        if($request->hasFile('avatar')):
            $file = $request->file('avatar');
            // Get filename with extension            
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('avatar')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/avatar/';
            $file->move($path, $fileNameToStore);
        else:
            $fileNameToStore = Auth::User()->avatar;
        endif;

        if($request->hasFile('signature')):
            $file = $request->file('signature');
            // Get filename with extension            
            $filenameWithExt = $request->file('signature')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
            // Get just ext
            $extension = $request->file('signature')->getClientOriginalExtension();
            //Filename to store
            $signature = time().'.'.$extension;                       
            // Upload Image
            $path = public_path().'/signature/';
            $file->move($path, $signature);
        else:
            $signature = Auth::User()->signature;
        endif;

        //Change Password
        $user = Auth::user();

        $user->username         = $request->get('username');
        $user->address          = $request->get('address');
        $user->email            = $request->get('email');
        $user->phone_number     = $request->get('phone_number');
        $user->Tel              = $request->get('Tel');
        $user->avatar           = $fileNameToStore;
        $user->signature        = $signature;

        $user->save();
        return redirect()->back()->with("success","Profile updated successfully!");
    }

    public function UserSearch(Request $request)
    {
        $name           = $request->name;
        $designation    = $request->designation;

        // name only
        if(isset($name) AND !isset($designation)):
            $Users = User::where('username', 'LIKE', $name.'%')->get();
        endif;

        // designation only
        if(!isset($name) AND isset($designation)):
            $Users = User::where('designation', $designation)->get();
        endif;

        // both
        if(isset($name) AND isset($designation)):
           $Users = User::where('username', 'LIKE', $name.'%')->where('designation', $designation)->get();
        endif;

        // none
        if(!isset($name) AND !isset($designation)):
            $Users = User::latest()->get();
        endif;

        return view('User.index', compact('Users'));
    }
}
