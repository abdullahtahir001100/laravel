<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Myuser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myusers = Myuser::all();
        return $myusers;
    }

   
    
    public function store(Request $request)
    {
        Myuser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return redirect('/user');
    }

 
    public function show()
    {
       $myusers =  Myuser::all();
        return view('user', compact('myusers'));
    }

    
    public function edit(string $id)
    {
        $myuser = Myuser::find($id);
        return view('edit_user', compact('myuser'));
    }

   
    public function update(Request $request, string $id)
    {
        $myuser = Myuser::find($id);

        $myuser->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return redirect('/user');
    }

    
    public function destroy(string $id)
    {
        Myuser::destroy($id);
        return redirect('/user');
    }
     public function reset(Request $request)
    {
       $reset =  Myuser::where('email', $request->email)->first();
        $reset->update([
            'password' => $request->password
        ]);
        return redirect('/user');
    }
    
}
