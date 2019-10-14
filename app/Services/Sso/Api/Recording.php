<?php

namespace App\Services\Sso\Api;

use App\Http\ErrorResponses;
use App\Http\StatusCode;
use Illuminate\Http\Request;

class Recording extends AbstractApi
{
    use ErrorResponses;
    /**
     * Get Session Record
     * @return mixed
     */
    public function index()
    {
        $response = $this->adapter->get(
            sprintf('%s', 'https://api.opentok.com/v2/partner/46157142/archive/'),
            [
              'headers' => [
                  'X-TB-PARTNER-AUTH' => '46157142:ad2e60e3b65b365f8b65e2572c6b820ffdea11d1'
              ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * auth acquire for agora
     *
     * @param Request $request
     * @return mixed
     */
    public function agoraAuth(Request $request)
    {
        $response = $this->adapter->post(
            sprintf('%s%s', config('live_session.agora_base_url'), config('live_session.agora_acquire')),
            [
                'headers' => [
                    'Authorization' => 'Basic YTJjY2MzM2JmZWJiNDIzMzk0OGNmNzEzNGU5NDUzMTQ6MzU3MjViYjg4ZTgzNGUwN2I5ZWQyZWUxMDQ1YTk3OGQ=',
                    'Content-Type' => 'application/json',
                ],

                'json' => [
                    'cname' => $request->input('cname'),
                    'uid' => $request->input('uid'),
                    'clientRequest' => $request->input('clientRequest') ?: new \stdClass(),
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Agora start recording
     *
     * @param Request $request
     * @param $rid
     * @return mixed
     */
    public function agoraStartRecord(Request $request, $rid)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s/%s', config('live_session.agora_base_url'), config('live_session.agora_resource_id'), $rid, config('live_session.agora_start')),
            [
                'headers' => [
                    'Authorization' => 'Basic YTJjY2MzM2JmZWJiNDIzMzk0OGNmNzEzNGU5NDUzMTQ6MzU3MjViYjg4ZTgzNGUwN2I5ZWQyZWUxMDQ1YTk3OGQ=',
                    'Content-Type' => 'application/json',
                ],

                'json' => [
                    'cname' => $request->input('cname'),
                    'uid' => $request->input('uid'),
                    'clientRequest' => $request->input('clientRequest') ?: new \stdClass(),
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Agora Stop recording
     *
     * @param Request $request
     * @param $rid
     * @param $sid
     * @return mixed
     */
    public function agoraStopRecord(Request $request, $rid, $sid)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s/%s%s/%s', config('live_session.agora_base_url'), config('live_session.agora_resource_id'), $rid, config('live_session.agora_source_id'), $sid, config('live_session.agora_stop')),
            [
                'headers' => [
                    'Authorization' => 'Basic YTJjY2MzM2JmZWJiNDIzMzk0OGNmNzEzNGU5NDUzMTQ6MzU3MjViYjg4ZTgzNGUwN2I5ZWQyZWUxMDQ1YTk3OGQ=',
                    'Content-Type' => 'application/json',
                ],

                'json' => [
                    'cname' => $request->input('cname'),
                    'uid' => $request->input('uid'),
                    'clientRequest' => $request->input('clientRequest') ?: new \stdClass(),
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Agora get status
     *
     * @param $rid
     * @param $sid
     * @return mixed
     */
    public function agoraStatus($rid, $sid)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s/%s%s/%s', config('live_session.agora_base_url'), config('live_session.agora_resource_id'), $rid, config('live_session.agora_source_id'), $sid, config('live_session.agora_status')),
            [
                'headers' => [
                    'Authorization' => 'Basic YTJjY2MzM2JmZWJiNDIzMzk0OGNmNzEzNGU5NDUzMTQ6MzU3MjViYjg4ZTgzNGUwN2I5ZWQyZWUxMDQ1YTk3OGQ=',
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Agora recording update
     *
     * @param Request $request
     * @param $rid
     * @param $sid
     * @return mixed
     */
    public function agoraUpdateRecord(Request $request, $rid, $sid)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s/%s%s/%s', config('live_session.agora_base_url'), config('live_session.agora_resource_id'), $rid, config('live_session.agora_source_id'), $sid, config('live_session.agora_update')),
            [
                'headers' => [
                    'Authorization' => 'Basic YTJjY2MzM2JmZWJiNDIzMzk0OGNmNzEzNGU5NDUzMTQ6MzU3MjViYjg4ZTgzNGUwN2I5ZWQyZWUxMDQ1YTk3OGQ=',
                    'Content-Type' => 'application/json',
                ],

                'json' => [
                    'cname' => $request->input('cname'),
                    'uid' => $request->input('uid'),
                    'clientRequest' => $request->input('clientRequest') ?: new \stdClass(),
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
