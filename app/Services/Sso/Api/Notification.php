<?php

namespace App\Services\Sso\Api;

class Notification extends AbstractApi
{
    public function sms($from, $phone, $message)
    {
        $response = $this->adapter->post(
            sprintf('%s', config('sso.infobip_url')),
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => config('sso.infobip_auth'),
                ],

                'json' => [
                    "from" => $from,
                    "to" => $phone,
                    "text" => $message
                ]
            ]
        );

         return json_decode($response->getBody()->getContents(), true);
    }
}
