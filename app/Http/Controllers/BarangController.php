<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        $barangs = Barang::all();
        return view('admin.barangs.index', compact('barangs'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.barangs.create');
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
            $data['image'] = $request->file('image')->store('barangs', 'public');
        }

        Barang::create($data);
        return redirect()->route('barangs.index')->with('success', 'Barang created successfully.');
    }

    public function edit(Barang $barang)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.barangs.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
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
            if ($barang->image) {
                Storage::disk('public')->delete($barang->image);
            }
            $data['image'] = $request->file('image')->store('barangs', 'public');
        }

        $barang->update($data);
        return redirect()->route('barangs.index')->with('success', 'Barang updated successfully.');
    }

    public function destroy(Barang $barang)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($barang->image) {
            Storage::disk('public')->delete($barang->image);
        }
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang deleted successfully.');
    }
}