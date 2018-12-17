<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
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


    public function setSchedule(Request $request)
    {
        $return = ['You Have No Access'];
        $user = Auth::user();
        $coach = $user->role_id == 2;

        if ($coach) {
            $schedules = Schedule::select('id')->where('user_id', $user->id);
            //select 'id' from table schedule where 'user_id' = id user yang sedang login.
            if (sizeof($schedules) == 0) {
                $return = ['message' => 'This is not yours'];
                return response()->json($return);
            } else {
                $schedule = Schedule::where('user_id', $user->id)
                    ->where('day', $request->input('day'))
                    ->first();
                $time = date_default_timezone_get(Auth::user());
                $t = Carbon::now()->tzName;

                echo date('l jS \of F Y h:i:s A Z e');
                echo PHP_EOL;
                echo $t;
                echo PHP_EOL;
                echo date("M d Y H:i:s Z e");
                echo PHP_EOL;
                echo gmdate("M d Y H:i:s Z e");
//                var_dump($time);
                exit();
                $schedule->start_time = gmdate($request->input('start_time'));
                $schedule->end_time = gmdate($request->input('end_time'));
                if ($schedule->save()) {
                    $return = ['status' => 200, 'message' => 'successful', 'data' => $schedule];
                } else {
                    $return = ['status' => 200, 'message' => 'Failed'];
                }
            }

        }

        return response()->json($return);
    }


    public function getSchedule()
    {
        $return = ['status' => 401, 'message' => 'failed'];
        $user = Auth::user();
        $coach = $user->role_id == 2;

        if ($coach) {
            $schedules = Schedule::where('user_id', $user->id)->get();
            $return = ['status' => 200, 'message' => "Here's You're Schedule", 'data' => $schedules];
        }

        return response()->json($return);
    }
}
