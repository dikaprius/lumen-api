<?php

namespace App\Services\Sso\Api;

use App\Http\ErrorResponses;
use App\Http\StatusCode;

class Call1 extends AbstractApi
{
    use ErrorResponses;
    /**
     * Get Call 1
     *
     * @param $server
     * @param $dynedPro
     * @return mixed
     */
    public function index($server, $dynedPro)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s', 'https://', $server, config('sso.call1')),
            [
                'json' => [
                    "commandName" => "QueryStudents",
                    "studentLoginId" => $dynedPro,
                    "studentActivity" => "true",
                    "studentPassword" => "",
                    "token" => "EBDB2769-019F-4F8D-9D6D-0B9DDEBF8089"
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
