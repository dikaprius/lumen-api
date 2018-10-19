<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                return response()->json(['status' => 'failed', 'message' => 'You Enter A Wrong Email'], 401);
            }
            $password = Hash::check($request->input('password'), $user->password);
            if (!$password) {
                return response()->json(['status' => 'failed', 'message' => 'You Enter A Wrong password'], 401);
            }
            if ($user->status == 'active'){
                $token = $this->jwt->attempt($request->only('email', 'password'));
            }else{
                return response()->json(['status' => 'failed', 'message' => 'Student is not actived yet'], 401);
            }



        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }
}