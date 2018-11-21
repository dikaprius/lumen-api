<?php

namespace App\Http\Controllers;

use App\Notification\RequestTokenNotification;
use App\Notification\RequestTokenPartnerNotification;
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
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function requestToken(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|integer'
        ]);

        $user = Auth::user();

        if (!$user->isStudent){
            return response()->json(["you're not a student"]);
        }

        $token = new Token();

        $token->status = 'requested';
        $token->token = $request->input('token');
        $token->user_id = $user->id;
        $token->approve_id = $user->profiles->partner_id;

        $token->save();

        $user->notify(new RequestTokenNotification());

        $partner = User::where('id', $user->profiles->partner_id)->first();
        $partner->notify(new RequestTokenPartnerNotification());

        return response()->json(['status' => 200, 'message' => 'request successfully', 'data' => $token]);
    }

    public function getRequestedToken()
    {
        $user = Auth::user();

        // Check if authenticated user is a partner
        if (!$user->isPartner()) {
            return response()->json(['message' => "No you can't !!! you're not a partner"]);
        }

        $token = Token::where('approve_id', $user->id)->where('status', 'requested')->get();

        return response()->json(['status' => 200, 'message' => 'The Token has requested', 'data' => $token]);
    }

    public function approveToken(Request $request)
    {
        $user = Auth::user();

        // Check if authenticated user is a partner
        if (!$user->isPartner()) {
            return response()->json(['message' => "No you can't !!! you're not a partner"]);
        }

        // Retrieve token from database
        $token = Token::find($request->input('id'));

        // Token not exist
        if (!$token) {
            return response()->json(['status' => 200, 'message' => 'no data', 'data' => []]);
        }

        // Token status is not requested, partner allow to accept or decline
        if ($token->status != 'requested') {
            return response()->json(['status' => 200, 'message' => 'data is not a request', 'data' => $token]);
        }

        $token->status = $request->input('action');
        $token->save();

        // Token new is accepted
        if ($request->input('action') == 'accept') {
            $tokenStudent = TokenStudent::where('user_id', $token->user_id)->first();
            $tokenStudent->token = $tokenStudent->token + $token->token;
            $tokenStudent->save();

            return response()->json(['status' => 200, 'message' => 'success', 'Amount Token Request' => $token->token, 'token' => $tokenStudent]);
        }

        // Token is not accepted
        return response()->json(['status' => 200, 'message' => 'failed', 'data' => $token]);
    }
}
