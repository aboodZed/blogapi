<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    //register
    public function register(Request $requset)
    {
        $validator = Validator::make($requset->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $requset->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('Abood')->accessToken;
        //"Abood" as a test you must add something strong as a pssword

        return $this->sendResponse($success, 'User register successfully');
    }

    //login
    public function login(Request $requset)
    {
        if (Auth::attempt(['email' => $requset->email, 'password' => $requset->password])) {
            $user = User::find(Auth::id());
            $success['token'] = $user->createToken('Abood')->accessToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User login successfully');
        } else {
            return $this->sendError('Please check your auth', ['error' => 'Unauthorised']);
        }
    }
}
