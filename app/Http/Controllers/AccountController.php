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
        $account = "";

        if ($request->action == "new") {
            // $account = $request->validate([
            //     "name" => "required|max:255",
            //     "username" => "required|unique:account,username|max:255",
            //     "password" => "required|max:255",
            //     "type" => "required",
            // ]);

            $account = Account::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'type' => $request->type,
            ]);
        } else if ($request->action == "update") {
            $account = Account::findOrFail($request->id);
            $account->name = $request->name;
            $account->username = $request->username;
            $account->password = bcrypt($request->password);
            $account->type = $request->type;
            $account->save();
        }

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
