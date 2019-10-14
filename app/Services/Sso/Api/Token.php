<?php

namespace App\Services\Sso\Api;

class Token extends AbstractApi
{
    /**
     * Is token SSO valid
     *
     * @param $token
     * @return bool
     */
    public function isValid($token)
    {
        $response = $this->adapter->post(
            sprintf('%s/%s', $this->endpoint, config('sso.token_verify')),
            [
                'json' => [
                    'token' => $token
                ]
            ]
        );

        if($response->getStatusCode() == '200') {
            return true;
        }

        return false;
    }
}