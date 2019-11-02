<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Product;
use Auth;

class HomeController extends Controller {
    public function index() {
    	if (Auth::check()) {
            $products = Product::get();

    		return view("admin.index", [
                "products" => $products,
            ]);
    	} else {
            return view("index.index");
    	}
    }
}
