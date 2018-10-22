<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function notification()
    {
        $user = Auth::user();
        $notif = $user->unreadNotifications;

        return response()->json($notif);
    }
}
