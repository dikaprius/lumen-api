<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Todo;
use App\TokenStudent;
use App\User;
use Carbon\Carbon;
use Dotenv\Validator;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

//    public function authenticate(Request $request)
//    {
//        $this->validate($request, [
//            'password' => 'required',
//            'email' => 'required'
//        ]);
//
//        $user = User::where('email', $request->input('email'))->first();
//        if (!$user) {
//            return response()->json(['status' => 'failed', 'message' => 'You Enter A Wrong Email'], 401);
//        }
//
//        if ($user->status == 'active') {
//            if (Hash::check($request->input('password'), $user->password)) {
//                $apikey = base64_encode(str_random(40));
//                User::where('email', $request->input('email'))
//                    ->update([
//                        'apikey' => $apikey,
//                        'last_login' => date('Y-m-d H:i:s')
//                    ]);
//
//                return response()->json(['status' => 'success', 'apikey' => $apikey, 'last login' => $user->last_login]);
//
//            } else {
//                return response()->json(['status' => 'failed', 'message' => 'You Enter Wrong password'], 401);
//            }
//        } else {
//            return response()->json(['status' => 'failed', 'message' => 'Student is not actived yet'], 401);
//        }
//    }

    public function register(Request $request)
    {
        $return = [];

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'userimage' => 'file|max:2000'
        ]);
        if ($request->hasFile('userimage')) {
            $image = $request->file('userimage');
            $path = Storage::putFile('avatar', $image);

//            run this command to create a symlink in lumen
//            ln -s /Users/dyned/lumen_api/storage/app/avatar /Users/dyned/lumen_api/public/avatar
        }

        $password = Hash::make($request->input('password'));

        $user = new User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = $password;
        $user->sso_username = NULL;
        $user->sso_enabled = NULL;
        if ($request->hasFile('userimage')) {
            $user->userimage = $path;
        }
        $user->role_id = 0;
        $user->last_login = date('Y-m-d H:i:s');
        $user->status = 'active';
        if ($user->save()) {
            $return = ['message' => 'Register Successfully', 'status' => 200, 'data' => $user];
        }

        return response()->json($return);
    }

    public function registerStudent(Request $request)
    {
        // Check if authenticated user is a partner
        if (!(Auth::user())->isPartner()) {
            return response()->json(['message' => "No you can't !!! you're not a partner"]);
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'userimage' => 'file|max:2000'
        ]);

        // Storing image
        $path = null;
        if ($request->hasFile('userimage')) {
            $image = $request->file('userimage');
            $path = Storage::putFile('avatar', $image);

//            run this command to create a symlink in lumen
//            ln -s /Users/dyned/lumen_api/storage/app/avatar /Users/dyned/lumen_api/public/avatar
//           ln -s /Users/dyned/Project/live-api/storage/app/avatar /Users/dyned/Project/live-api/public/avatar

        }

        // Store student
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
//      $user->password = bcrypt($request->input('password'));
        $user->sso_username = NULL;
        $user->sso_enabled = NULL;
        $user->userimage = $path;
        $user->role_id = 1;
        $user->last_login = date('Y-m-d H:i:s'); //now()
        $user->status = 'active';
        $user->save();

        // Store student profile
        $userProfile = new Profile();
        $userProfile->partner_id = Auth::id();
        $userProfile->user_id = $user->id;
        $userProfile->save();

        // Store student token
        $token = new TokenStudent();
        $token->user_id = $user->id;
        $token->partner_id = Auth::id();
        $token->token = $request->input('token');
        $token->save();

        return response()->json(['message' => 'Register Successfully', 'status' => 200, 'data' => $user, 'token' => $token->token]);
    }

    public function profileByEmail($emailOrId)
    {
        $return = ['status' => 401, 'message' => 'You Have No Access'];
        $validateRole = Auth::user()->role_id == 0;

        if ($validateRole) {
            $user = User::active()->where(function($query) use ($emailOrId){
                $query->where('email', $emailOrId)
                    ->orWhere('id', $emailOrId);
            })->get();

            // if(!$user->count())
            if (sizeof($user) == 0) {
                $message = "There's no Student with this record";
            } else {
                $message = "Data Exist";
            }
            $return = ['status' => 200, 'message' => $message, 'data' => $user];
        }
        return response()->json($return);
    }

    public function getAllProfile()
    {
        $return = [];
        $validateRole = Auth::user()->role_id == 0;
        if ($validateRole) {
            $users = User::All();
            if (!$users) {
                $return = ['status' => 200, 'message' => 'Theres no Student with this ID'];
            } else {
                $return = ['status' => 200, 'message' => 'success', 'data' => $users];
            }
        } else {
            $return = ['status' => 401, 'message' => 'You Have No Access'];
        }

        return response()->json($return);
    }

    public function myProfile()
    {
        $user = Auth::user();

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $validate = $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required | confirmed',
            'password_confirmation' => 'required'
        ]);

        if (!$validate){
            return response()->json($validate);
        }
        $return = [];
        $user = User::find(Auth::id());
        $oldPassword = Auth::user()->password;
        $oldPasswordInput = $request->input('old_password');

        if (Hash::check($oldPasswordInput, $oldPassword)) {
            $user->password = Hash::make($request->input('password'));
            if ($user->save()) {
                $return = ['status' => 200, 'message' => 'password changed'];
            } else {
                $return = ['status' => 200, 'message' => 'password fails'];
            }
        } else {
            $return = ['status' => 201, 'message' => ' Wrong Old password'];
        }
        return response()->json($return);
    }

    public function changeImage(Request $request)
    {
        $return = [];
        $this->validate($request, [
            'userimage' => 'required|file|max:2000'
        ]);

        $user = Auth::user();
        if ($user) {
            if ($request->hasFile('userimage')) {
                $image = $request->file('userimage');
                $path = Storage::putFile('avatar', $image);
                $user->userimage = $path;
                if ($user->save()) {
                    $return = ['status' => 200, 'message' => 'Profile Has Changed', 'data' => $user];
                }
            }
        } else {
            $return = ['status' => 200, 'message' => 'nothing changes'];
        }
        return response()->json($return);
    }


}
