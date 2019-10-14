<?php

namespace App\Services\Sso\Api;

use App\Http\ErrorResponses;
use App\Services\Sso\Sso;
use Illuminate\Http\Request;

class PTScore extends AbstractApi
{
    use ErrorResponses;
    /**
     * Get user's pt_score from call 2
     *
     * @param $server
     * @param $dynedPro
     * @return mixed
     */
    public function index($server, $dynedPro)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s%s%s','https://', $server, config('sso.pt_score'), $dynedPro , '/summary'),
            [
                'headers' => [
                    'Authorization' => config('sso.call2_token')
                ],
            ]
        );

        return response()->json([
            'data' => json_decode($response->getBody()->getContents()),
            'status_code' => $response->getStatusCode()
        ]);
    }

    /**
     * Get B2c study data
     *
     * @param Request $request
     * @return mixed
     */
    public function b2c(Request $request)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.study_progress')),
            [
                'headers' => [
                      'X-Dyned-Tkn' => $request->header('Authorization'),
                      'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get student weekly progress
     *
     * @param $request
     * @return mixed
     */
    public function b2cWeekly($request)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.study_record')),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * get student's study data by coach
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function getByCoach($request, $email)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.study_progress_by_coach'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get Student's study data by admin
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function getByAdmin($request, $email)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.study_progress_by_admin'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get student's weekly progress by coach
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function getWeeklyByCoach($request, $email)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.study_record_by_coach'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get student's weekly progress by admin
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function getWeeklyByAdmin($request, $email)
    {
        $response = $this->adapter->get(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.study_record_by_admin'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Get student's summary
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function studentSummary($request, $email)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.student_summary'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if ($response->getStatusCode() != 200) {
            return json_decode($response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Student's summary by coach
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function studentSummaryByCoach($request, $email)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.student_summary_by_coach'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if ($response->getStatusCode() != 200) {
            return json_decode($response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Student's summary by admin
     *
     * @param $request
     * @param $email
     * @return mixed
     */
    public function studentSummaryByAdmin($request, $email)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.student_summary_by_admin'), $email),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if ($response->getStatusCode() != 200) {
            return json_decode($response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * get variable points
     *
     * @return mixed
     */
    public function variablePoints()
    {
        $token = Sso::superAdmin()->login()['token'];

        $response = $this->adapter->get(
            sprintf('%s%s%s', $this->endpoint, config('sso.dsa'), config('sso.variable_points')),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $token,
                    'Content-Type' => 'application/json'
                ],
            ]
        );

        if($response->getStatusCode() != 400){
            return json_decode($response->getBody()->getContents(), true);
        }

        return json_decode($response->getBody()->getContents());
    }
}
