<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Auth;

use Printer;
use DummyPrintConnector;
use CapabilityProfile;

class HomeController extends Controller {
    public function index() {
    	if (Auth::check()) {
    		return view("admin.index");
    	} else {
   //  		$connector = new DummyPrintConnector();
			// $profile = CapabilityProfile::load("simple");
			// $printer = new Printer($connector);
			// $printer -> text("Hello world!\n");
			// $printer -> cut();

    		return view("index.index", [
    			// "print" => $connector->getData(),
    			// "print64" => base64_encode($connector->getData()),
    		]);
    	}
    }
}
