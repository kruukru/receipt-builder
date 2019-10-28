<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Auth;

class HomeController extends Controller {
    public function index() {
    	if (Auth::check()) {
    		return view("admin.index");
    	} else {
    		return view("index.index");
    	}
    }
}
