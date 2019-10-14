<?php

namespace App\Services\Sso\Api;

use App\Services\Sso\Sso;

class SubGroup extends AbstractApi
{
    /**
     * Matching user and subgroup in SSO
     *
     * @param $groupId
     * @param $email
     * @return mixed
     */
    public function index($groupId, $email)
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
                        'subgroup_id' => $groupId
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}