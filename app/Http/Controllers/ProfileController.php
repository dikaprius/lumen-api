<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Profile;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
        $return = [];
        $user = Auth::user()->id;
        $profile =  new Profile();
        $profile->user_id = $user;
        $profile->address = $request->input('address');
        $profile->country = $request->input('country');
        $profile->city = $request->input('city');
        $profile->gender = $request->input('gender');
        $profile->phone = $request->input('phone');
        if ($profile->save()){
            $return = ['status' => 200, 'message' => 'success', 'data'=> $profile];
        }else{
            $return = ['status' => 200, 'message' => 'failed'];
        }

        return response()->json($return);
    }
}
