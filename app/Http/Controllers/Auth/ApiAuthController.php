<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthController extends Controller
{
    public function register (Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'integer',
            'teachers_code' => 'required|string|min:4',
        ]);

        if ($request['type'] == 1) {

            $validator->sometimes('teachers_code', 'unique:users', function ($input) {

                $user = User::where([
                    'type' => 1,
                    'teachers_code' => $input->teachers_code
                ])->count();

                return $user > 0;
            });
        }

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['type'] = $request['type'] ? $request['type']  : 0; // type 0 student , 1 teacher
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];

        return response($response, 200);
    }

    public function login (Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|min:6|confirmed',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
            //throw new ApiException("test error message from api exception", Response::HTTP_BAD_GATEWAY);
            //throw new ApiException(['errors'=>$validator->errors()->all()], Response::HTTP_BAD_REQUEST);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {

                $objToken = $user->createToken('Laravel Password Grant Client');

                $token = $objToken->accessToken;
                $expiration = $objToken->token->expires_at->diffForHumans();
                $response = ['user' => $user, 'access_token' => $token, 'expires_at' => $expiration];

                return response($response, 200);

            } else {

                $response = ["message" => "Password mismatch"];

                return response($response, 422);
            }

        } else {

            $response = ["message" =>'User does not exist'];

            return response($response, 422);
        }
    }

    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }
}
