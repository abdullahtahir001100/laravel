<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simple;

class SimpleController extends Controller
{
    // SHOW ALL
    public function index()
    {
        $simples = Simple::all();
        return view('simple', compact('simples'));
    }

    // CREATE
    public function create(Request $request)
    {
        Simple::create([
            'name' => $request->name
        ]);

        return redirect('/simple');
    }

    // UPDATE
    public function edit(Request $request, $id)
    {
        $simple = Simple::find($id);

        $simple->update([
            'name' => $request->name
        ]);

        return redirect('/simple');
    }

    // DELETE
    public function destroy($id)
    {
        Simple::destroy($id);
        return redirect('/simple');
    }
}
