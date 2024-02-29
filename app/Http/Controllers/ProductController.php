<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy("id", "DESC")->paginate(5)->toArray();
        $response = [
            "total_count" => $products["total"],
            "limit" => $products["per_page"],
            "pagination" => [
                "next_page" => $products["next_page_url"],
                "current_page" => $products["current_page"]
            ],
            "data" => $products["data"]
        ];

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $codeUnique = false;
        $code_barang = null;
        while (!$codeUnique) {
            $code_barang = $request->input('category') . '-' . mt_rand(100, 999);
            $existingProduct = Product::where('code_barang', $code_barang)->exists();
            if (!$existingProduct) {
                $codeUnique = true;
            }
        }

        $product = Product::create([
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'code_barang' => $code_barang,
            'price' => $request->input('price'),
            'qyt' => $request->input('qyt'),
        ]);

        return response()->json($product, 201);
    }


    public function update(Request $request, $id)
    {
        $input = $request->all();

        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        $product->fill($input);
        $product->save();

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        $product->delete();
        $message = ['message' => 'deleted successfully', 'id' => $id];
        return response()->json($message, 200);
    }
}
