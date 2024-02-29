<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy("id", "DESC")->paginate(5)->toArray();
        $response = [
            "total_count" => $orders["total"],
            "limit" => $orders["per_page"],
            "pagination" => [
                "next_page" => $orders["next_page_url"],
                "current_page" => $orders["current_page"]
            ],
            "data" => $orders["data"]
        ];

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        return response()->json($order, 200);
    }

    public function store(Request $request)
    {
        $product = Product::findOrfail($request->input('product_id'));
        $qyt = $request->input('qyt');
        $userId = Auth::user()->id;

        if ($qyt > $product->qyt) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        $product->qyt -= $qyt;
        $product->save();

        $order = Order::create([
            'product_id' => $product->id,
            'name' => $product->name,
            'qyt' => $request->input('qyt'),
            'price' => $product->price,
            'total_price' => $product->price * $qyt,
            'user_id' => $userId
        ]);

        return response()->json($order, 201);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        $order->delete();
        $message = ['message' => 'deleted successfully', 'id' => $id];
        return response()->json($message, 200);
    }
}
