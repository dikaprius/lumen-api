<?php

namespace App\Http\Controllers;

use App\Schedule;
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

        if ($coach){
            $schedules = Schedule::select('id')->where('user_id', $user->id);
            if (sizeof($schedules) == 0){
                $return = ['message'=>'This is not yours'];
                return response()->json($return);
            }else{
                $id = $request->id;
                $schedule = Schedule::find($id);
                $schedule->start_time = $request->input('start_time');
                $schedule->end_time = $request->input('end_time');
                if ($schedule->save()){
                    $return = ['status' => 200, 'message' => 'successful', 'data' => $schedule];
                }else{
                    $return = ['status' => 200, 'message' => 'Failed'];
                }
            }

        }

        return response()->json($return);
    }
}
