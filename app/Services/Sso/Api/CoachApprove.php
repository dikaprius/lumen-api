<?php

namespace App\Services\Sso\Api;

use App\Services\Sso\Sso;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoachApprove extends AbstractApi
{

    /**
     * Approve Coach By Coach Partner
     * Using super admin account
     * (Super admin has been logged in)
     *
     * @param $email
     * @return mixed
     */
    public function index($status, $email)
    {
        $token = Sso::superAdmin()->login()['token'];

        $response = $this->adapter->patch(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.user'), config('sso.update_profile'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $token,
                    "Content-Type" => "application/json"
                ],

                'json' => [
                    'acl' => [
                        'account' => [
                            'active' => $status,
                        ],

                        'live' => [
                            'access' => [
                                $status
                            ],
                        ],
                    ],
                    'profile' => new \stdClass() //send as an empty object
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Approve Coach Coach/Student partner
     *
     * @param $status
     * @param $email
     * @return mixed
     */
    public function approvePartner($status, $email)
    {
        $token = Sso::superAdmin()->login()['token'];

        $response = $this->adapter->patch(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.user'), config('sso.update_profile'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $token,
                    "Content-Type" => "application/json"
                ],

                'json' => [
                    'acl' => [
                        'account' => [
                            'active' => $status,
                        ],
                    ],
                    'profile' => new \stdClass() //send as an empty object
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
