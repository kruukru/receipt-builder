<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Auth;
use Response;
use Validator;

class AccountController extends Controller
{
    public function getAccount() {
        $accounts = Account::where('id', '!=', 1)->get();

        return view("admin.account", [
            "accounts" => $accounts,
        ]);
    }

    public function postSaveAccount(Request $request) {
        $account = "";

        if ($request->action == "new") {
            // start validation
            $validator = Validator::make($request->all(), [
                "name" => "required|max:255",
                "username" => "required|unique:account,username|max:255",
                "password" => "required|max:255",
                "type" => "required",
            ]);
            if ($validator->fails()) {
                return Response::json(array(
                    'status' => "ERROR",
                    'data' => $validator->errors(),
                ));
            }
            // end validation

            $account = Account::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'type' => $request->type,
            ]);
        } else if ($request->action == "update") {
            // start validation
            $arrayValidation = array(
                "name" => "required|max:255",
                "username" => "required|unique:account,username,".$request->id."|max:255",
                "type" => "required",
            );
            if ($request->password != "") {
                $arrayValidation["password"] = "required|max:255";
            }
            $validator = Validator::make($request->all(), $arrayValidation);
            if ($validator->fails()) {
                return Response::json(array(
                    'status' => "ERROR",
                    'data' => $validator->errors(),
                ));
            }
            // end validation

            $account = Account::findOrFail($request->id);
            $account->name = $request->name;
            $account->username = $request->username;
            if ($request->password != "") {
                $account->password = bcrypt($request->password);
            }
            $account->type = $request->type;
            $account->save();
        }

        return Response::json(array(
            'status' => "OK",
            'data' => $account,
        ));
    }
    public function postRemoveAccount(Request $request) {
        $account = Account::findOrFail($request->id);
        $account->delete();

        return Response::json(array(
            'status' => "OK",
            'data' => $account,
        ));
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
