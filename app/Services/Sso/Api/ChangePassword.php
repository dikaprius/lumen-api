<?php


namespace App\Services\Sso\Api;

class ChangePassword extends AbstractApi
{
    /**
     * Change User's password
     *
     * @param $request
     * @param $old
     * @param $new
     * @param $confirm
     * @return mixed
     */
    public function student($request, $old, $new, $confirm)
    {
        $response = $this->adapter->patch(
            sprintf('%s%s%s', $this->endpoint, config('sso.dsa'), config('password.change_password')),
            [
                'headers' => [
                    'X-Dyned-Tkn' => $request->header('Authorization'),
                    'Content-Type' => 'application/json'
                ],

                'json' => [
                    'old_password' => $old,
                    'new_password' => $new,
                    'confirm_password' => $confirm
                ]
            ]
        );

        if ($response->getStatusCode() != 200) {
            return $response->getStatusCode();
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * forgot password
     *
     * @param $email
     * @return mixed
     */
    public function forgot($email)
    {
        $response = $this->adapter->post(
            sprintf('%s%s%s', $this->endpoint, config('sso.dsa'), config('password.reset_password')),
            [
                'form_params' => [
                    'email' => $email
                ]
            ]
        );

        if ($response->getStatusCode() != 200) {
            return $response->getStatusCode();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
