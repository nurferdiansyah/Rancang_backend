<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter produk baru
        if ($request->has('is_new')) {
            $query->where('is_new', true);
        }

        // Filter rekomendasi
        if ($request->has('is_recommended')) {
            $query->where('is_recommended', true);
        }

        // Search by name
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data'   => $products,
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $product,
        ]);
    }
}