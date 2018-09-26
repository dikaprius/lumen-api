<?php

namespace App\Http\Controllers;

use App\Todo;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
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

    public function authenticate(Request $request)
    {
      $this->validate($request, [
        'password' => 'required',
        'email' =>'required'
      ]);

      $user = User::where('email',$request->input('email'))->first();
      if(!$user){
          return response()->json(['status'=> 'failed', 'message' => 'You Enter A Wrong Email'], 401);
      }

      if(Hash::check($request->input('password'), $user->password)){
        $apikey = base64_encode(str_random(40));
        User::where('email',$request->input('email'))
              ->update([
                'apikey' => $apikey
              ]);

        return response()->json(['status' => 'success', 'apikey' => $apikey]);

      }else{
        return response()->json(['status'=> 'failed', 'message'=>'You Enter Wrong password'], 401);
      }
    }

    public function register(Request $request)
    {
      $return = [];

      $this->validate($request, [
        'name' => 'required',
        'email' => 'required|unique:users',
        'password'=> 'required'
      ]);

      $password = Hash::make($request->input('password'));

      $user = new User;
      $user->name = $request->get('name');
      $user->email = $request->get('email');
      $user->password = $password;
      $user->name = $request->get('name');
      $user->userimage = $request->userimage;
      if($user->save()){
        $return = ['message' => 'Register Successfully', 'status'=>200, 'data'=>$user];
      }

      return response()->json($return);
    }

    public function login()
    {
      return view('login');
    }

}
