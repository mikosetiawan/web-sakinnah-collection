<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Jasa;
use App\Models\Barang;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', auth()->id())->with(['jasa', 'barang'])->get();
        return view('cart.index', compact('carts'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'type' => 'required|in:jasa,barang',
            'id' => 'required|integer',
        ]);

        $type = $request->type;
        $itemId = $request->id;

        // Check if item already in cart
        $existing = Cart::where('user_id', auth()->id())
            ->where($type . '_id', $itemId)
            ->first();

        if ($existing) {
            return redirect()->route('cart.index')->with('error', 'Item already in cart.');
        }

        Cart::create([
            'user_id' => auth()->id(),
            'jasa_id' => $type === 'jasa' ? $itemId : null,
            'barang_id' => $type === 'barang' ? $itemId : null,
        ]);

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}