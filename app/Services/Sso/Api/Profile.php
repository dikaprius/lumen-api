<?php

namespace App\Services\Sso\Api;

use App\Http\Country;
use App\Services\Sso\Sso;
use Illuminate\Http\Request;

class Profile extends AbstractApi
{
    /**
     * Is token SSO valid
     *
     * @param $token
     * @return bool
     */
    public function token($token)
    {
        $email = $this->parse($token)->get('payload')->username;

        $response = $this->adapter->get(
            sprintf('%s/%s/%s', $this->endpoint, config('sso.profile'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $token
                ]
            ]
        );

        if ($response->getStatusCode() == '200') {
            return $response->getBody()->getContents();
        }

        return null;
    }

    /**
     * Parse JWT into readable header and payload
     *
     * @param $token
     * @return array
     */
    public function parse($token)
    {
        list($header, $payload, $signature) = explode('.', $token);

        return collect([
            'header' => json_decode(base64_decode($header)),
            'payload' => json_decode(base64_decode($payload)),
        ]);
    }

    /**
     * Update user's profile
     * Using superadmin account
     *
     * @param Request $request
     * @param $email
     * @return mixed
     */
    public function update(Request $request, $email)
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
                    'acl' => new \stdClass(), //send as an empty object
                    'profile' => [
                        'fullname'        => $request->input('fullname') ?: '',
                        'nickname'        => $request->input('nickname') ?: '',
                        'gender'          => $request->input('gender') ?: '',
                        'country_iso'     => Country::getIso($request) ?: '',
                        'dial_code'       => Country::getDialCode($request) ?: '',
                        'phone'           => $request->input('phone') ?: '',
                        'date_of_birth'   => $request->input('date_of_birth')?: '',
                        'address'         => $request->input('address')?: '',
                        'city'            => $request->input('city')?: '',
                        'state'           => $request->input('state')?: '',
                        'zip'             => $request->input('zip')?: '',
                        'spoken_language' => $request->input('spoken_language')?: '',
                        'user_language'   => $request->input('user_language')?: '',
                        'short_bio'       => $request->input('short_bio')?: '',
                        'student_like'    => $request->input('student_like')?: '',
                        'student_dislike' => $request->input('student_dislike')?: '',
                        'student_hobby'   => $request->input('student_hobby') ?: ''
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Update user's avatar
     * using superadmin account
     *
     * @param Request $request
     * @param $email
     * @return mixed
     */
    public function changeAvatar($path, $email)
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
                    'acl' => new \stdClass(),
                    'profile' => [
                        'avatar' => url($path)
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     *
     *
     * @param Request $request
     * @param $minutes
     * @param $email
     * @return mixed
     *
     * Set user's timezone in SSO
     */
    public function timezone(Request $request, $email)
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
                    'acl' => new \stdClass(),
                    'profile' => [
                        'timezone' => $request->input('timezone'),
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Update user's status enabled/disabled
     *
     * @param $status
     * @param $email
     * @return mixed
     */
    public function updateStatus($status, $email)
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
     * Add Coach's rating
     *
     * @param $rounded
     * @param $email
     * @return mixed
     *
     */
    public function coachRating($rounded, $email)
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
                    'acl' => new \stdClass(),
                    'profile' => [
                        'coach_rating' => $rounded,
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get user acl and profile by admin
     *
     * @param $email
     * @return mixed
     */
    public function getOtherSso($email)
    {
        $token = Sso::superAdmin()->login()['token'];

        $response = $this->adapter->get(
            sprintf('%s%s/%s%s%s', $this->endpoint, config('sso.user'), config('sso.user_superadmin'), config('sso.get'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $token,
                    "Content-Type" => "application/json"
                ],
            ]
        );

        if ($response->getStatusCode() != 200) {
            return null;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
