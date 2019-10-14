<?php

namespace App\Services\Sso\Api;

class SuperAdmin extends AbstractApi
{
    /**
     * Hit the SSO endpoint to Logging in Superadmin
     * Because, only Superadmin can update the user's profile
     *
     * @return bool|mixed
     */
    public function login()
    {
        $response = $this->adapter->post(
            sprintf('%s/%s', $this->endpoint, config('sso.token_request')),
            [
                'json' => [
                    'username' => config('sso.user_superadmin'),
                    'password' => config('sso.password_superadmin'),
                ]
            ]
        );

        if($response->getStatusCode() == '200') {
            return json_decode($response->getBody()->getContents(),true);
        }

        return false;
    }
}