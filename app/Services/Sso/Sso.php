<?php

namespace App\Services\Sso;

use App\Services\Sso\Adapter\GuzzleHttpAdapter;
use App\Services\Sso\Api\Call1;
use App\Services\Sso\Api\ChangePassword;
use App\Services\Sso\Api\CoachApprove;
use App\Services\Sso\Api\Notification;
use App\Services\Sso\Api\Profile;
use App\Services\Sso\Api\PTScore;
use App\Services\Sso\Api\Recording;
use App\Services\Sso\Api\SubGroup;
use App\Services\Sso\Api\SuperAdmin;
use App\Services\Sso\Api\Token;
use App\Services\Sso\Api\User;

class Sso
{
    /**
     * Return token API
     *
     * @return Token
     */
    public static function token()
    {
        return new Token(new GuzzleHttpAdapter());
    }

    /**
     * Return profile API
     *
     * @return Profile
     */
    public static function profile()
    {
        return new Profile(new GuzzleHttpAdapter());
    }

    /**
     * Create/add a new student by Student Affiliate
     *
     * @return User
     */
    public static function user()
    {
        return new User(new GuzzleHttpAdapter());
    }

    /**
     * Logging in the SuperAdmin to update user's profile
     *
     * @return SuperAdmin
     */
    public static function superAdmin()
    {
        return new SuperAdmin(new GuzzleHttpAdapter());
    }

    /**
     * Approve the coach by coach partner
     *
     * @return CoachApprove
     *
     */
    public static function coachApprove()
    {
        return new CoachApprove(new GuzzleHttpAdapter());
    }

    /**
     * Retrieve user's pt_score
     *
     * @return PTScore
     */
    public static function PTScore()
    {
        return new PTScore(new GuzzleHttpAdapter());
    }

    /**
     * Update/add user to a subgroup
     *
     * @return SubGroup
     */
    public static function subGroup()
    {
        return new SubGroup(new GuzzleHttpAdapter());
    }

    /**
     * Get Call 1
     *
     * @return Call1
     */
    public static function call1()
    {
        return new Call1(new GuzzleHttpAdapter());
    }

    /**
     * Send Sms Notification
     *
     * @return Notification
     */
    public static function notification()
    {
        return new Notification(new GuzzleHttpAdapter());
    }

    /**
     * Change user's password
     *
     * @return ChangePassword
     */
    public static function password()
    {
        return new ChangePassword(new GuzzleHttpAdapter());
    }

    /**
     * Check user's recording
     *
     * @return Recording
     */
    public static function recording()
    {
        return new Recording(new GuzzleHttpAdapter());
    }

}
