<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Token;
use App\TokenStudent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function requestToken(Request $request)
    {
        $return = ["you're not a student"];
        $this->validate($request, [
            'token' => 'required | integer'
        ]);

        $student = Auth::user()->role_id == 1;
        $user = Auth::user();

        if ($student) {
            $token = new Token();
            $token->status = 'requested';
            $token->token = $request->input('token');
            $token->user_id = Auth::user()->id;
            $token->approve_id = $user->profiles->partner_id;
            if ($token->save()) {
                $return = ['status' => 200, 'message' => 'request successfully', 'data' => $token];
            }
        }
        return response()->json($return);
    }

    public function getRequestedToken()
    {
        $return = ['message' => "No you can't !!! you're a student"];
        $user = Auth::user();
        $partner = $user->role_id == 0;


        if ($partner) {
            $token = Token::where('approve_id', $user->id)->get();
            $return = ['status' => 200, 'message' => 'The Token has requested', 'data' => $token];
        }
        return response()->json($return);
    }

    public function approveToken(Request $request)
    {
        $return = ['message' => "No you can't !!! you're a student"];
        $user = Auth::user();
        $partner = $user->role_id == 0;

        if ($partner) {
            $id = $request->id;
            $action = $request->input('action');
            $token = Token::find($id);

            if (!$token) {
                $return = ['status' => 200, 'message' => 'failed', 'data' => []];
                return response()->json($return);
            }

            $token->status = $action;

            if ($token->save()) {
                if ($action == 'accept' && $token->status == 'requested') {
                    $amountRequest = $token->token;
                    $user_idRequest = $token->user_id;
                    $add = TokenStudent::select('token')->where('user_id', $user_idRequest)->first();
                    $final = $amountRequest + $add->token;
                    $update = TokenStudent::where('user_id', $user_idRequest);
                    $update->update([
                        'token' => $final
                    ]);

                    $return = ['status' => 200, 'message' => 'success', 'Amount Token Request' => $amountRequest, 'token' => $update->get()];

                } else {
                    $return = ['status' => 200, 'message' => 'failed'];
                }
            }
        }
        return response()->json($return);
    }
}