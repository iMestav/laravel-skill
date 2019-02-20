<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('order');
    }


    public function store(Request $request)
    {
        // return $request->all();
        // Create Json Data;
        $orders = [];
        foreach ($request->product as $key => $product) {
            $orders[$key] = [
                'product_name' => $request->product[$key],
                'qty' => $request->qty[$key],
                'price' => $request->price[$key],
                'date' => $request->date[$key],
                'total' => $request->total[$key],
            ];
        }
        // return $orders;
        $orders['total'] = [
            'total_value_before_tax' => $request->total_value_before_tax,
            'total_tax_value' => $request->total_tax_value,
            'total_value' => $request->total_value
        ];
        // Save json data to file
        file_put_contents(base_path('orders.json'),json_encode($orders, JSON_PRETTY_PRINT));
        return response()->json([
            'messsage' => 'Success'
        ]);
    }
}
