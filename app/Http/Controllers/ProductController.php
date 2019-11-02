<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use App\Product;

class ProductController extends Controller
{
    public function getProduct() {
    	$products = Product::get();

    	return view('admin.product', [
    		"products" => $products,
    	]);
    }

    public function postSaveProduct(Request $request) {
        $product = "";

        if ($request->action == "new") {
            // start validation
            $validator = Validator::make($request->all(), [
                "name" => "required|max:255",
                "price" => "required|numeric|max:1000000",
            ]);
            if ($validator->fails()) {
                return Response::json(array(
                    'status' => "ERROR",
                    'data' => $validator->errors(),
                ));
            }
            // end validation

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);
        } else if ($request->action == "update") {
            // start validation
            $arrayValidation = array(
                "name" => "required|max:255",
                "price" => "required|numeric|max:1000000",
            );
            $validator = Validator::make($request->all(), $arrayValidation);
            if ($validator->fails()) {
                return Response::json(array(
                    'status' => "ERROR",
                    'data' => $validator->errors(),
                ));
            }
            // end validation

            $product = Product::findOrFail($request->id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->save();
        }

        return Response::json(array(
            'status' => "OK",
            'data' => $product,
        ));
    }
    public function postRemoveProduct(Request $request) {
        $product = Product::findOrFail($request->id);
        $product->delete();

        return Response::json(array(
            'status' => "OK",
            'data' => $product,
        ));
    }
}
