<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Auth;
use Response;

class AccountController extends Controller
{
    public function getAccount() {
        $accounts = Account::get();

        return view("admin.account", [
            "accounts" => $accounts,
        ]);
    }
    public function postAccountSave(Request $request) {
        $account = Account::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'type' => $request->type,
        ]);

        return Response::json($account);
    }

    public function postLogin(Request $request) {
        $arrayLogin = array(
            'username' => $request->username, 
            'password' => $request->password,
        );

    	if (Auth::attempt($arrayLogin)) {
    		return Response::json("SUCCESS");
    	} else {
    		return Response::json("FAIL");
    	}
    }

    public function getLogout() {
        Auth::logout();

        return redirect()->route('home');
    }
}
