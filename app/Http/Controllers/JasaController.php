<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JasaController extends Controller
{

    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        $jasas = Jasa::all();
        return view('admin.jasas.index', compact('jasas'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.jasas.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('jasas', 'public');
        }

        Jasa::create($data);
        return redirect()->route('jasas.index')->with('success', 'Jasa created successfully.');
    }

    public function edit(Jasa $jasa)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.jasas.edit', compact('jasa'));
    }

    public function update(Request $request, Jasa $jasa)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            if ($jasa->image) {
                Storage::disk('public')->delete($jasa->image);
            }
            $data['image'] = $request->file('image')->store('jasas', 'public');
        }

        $jasa->update($data);
        return redirect()->route('jasas.index')->with('success', 'Jasa updated successfully.');
    }

    public function destroy(Jasa $jasa)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($jasa->image) {
            Storage::disk('public')->delete($jasa->image);
        }
        $jasa->delete();
        return redirect()->route('jasas.index')->with('success', 'Jasa deleted successfully.');
    }
}