<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $request->quantity,
            ]);
        } else {
            $cart = Cart::create([
                'user_id'    => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk ditambahkan ke keranjang',
            'data'    => $cart->load('product'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$cart) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item tidak ditemukan',
            ], 404);
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Keranjang diperbarui',
            'data'    => $cart->load('product'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$cart) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item tidak ditemukan',
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Item dihapus dari keranjang',
        ]);
    }
}