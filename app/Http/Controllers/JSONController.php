<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Product;
use Response;

class JSONController extends Controller
{
	public function getAccountOne(Request $request) {
		$account = Account::findOrFail($request->id);

		return Response::json($account);
	}

	public function getProductOne(Request $request) {
		$product = Product::findOrFail($request->id);

		return Response::json($product);
	}
}
