<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends Api_Controller
{
    protected function expires($additional = 0)
    {
        return time() + (env('EXPIRED_TOKEN') + $additional);
    }

    public function login_post()
    {
        $email = $this->post('email');
        if (!$email) $this->returnResponse(true, "Email Harus di isi", 422);
        $payload = [
            'email' => $email,
            'exp' => $this->expires()
        ];

        $refreshPayload = [
            'email' => $email,
            'exp' => $this->expires(60 * 5)
        ];

        $accessToken = JWT::encode($payload, env('ACCESS_TOKEN_SECRET'), 'HS256');
        $refreshToken = JWT::encode($refreshPayload, env('REFRESH_TOKEN_SECRET'), 'HS256');
        setcookie("refresh", $refreshToken, $this->expires(60 * 5), '', '', false, true);
        $this->returnResponse([
            'access_token' => $accessToken,
            'expired' => date(DATE_ATOM, $payload['exp']),
        ]);
    }

    public function refresh_post()
    {
        $token = $this->post('token');
        if (!$token) $this->returnResponse(true, "Refresh token tidak ditemukan", 422);
        try {
            $decode = $this->checkToken($token);
            if ($decode) {
                $this->returnResponse(null, "Token masih aktif. refresh token berhasil");
            }
        } catch (Exception $e) {
            // $this->returnResponse(true, $e->getMessage(), 403);
            $cookie = $_COOKIE["refresh"] ?? null;
            if (!isset($cookie)) $this->returnResponse(null, "Token is expired!", 403);
            $decodeRefresh = JWT::decode($cookie, new Key(env('REFRESH_TOKEN_SECRET'), 'HS256'));
            if (!$decodeRefresh) $this->returnResponse(null, "Token is expired!", 403);
            $payload = [
                'email' => $decodeRefresh->email,
                'exp' => $this->expires()
            ];

            $refreshPayload = [
                'email' => $decodeRefresh->email,
                'exp' => $this->expires(60 * 5)
            ];
            $newAccessToken = JWT::encode($payload, env('ACCESS_TOKEN_SECRET'), 'HS256');
            $newRefreshToken = JWT::encode($refreshPayload, env('REFRESH_TOKEN_SECRET'), 'HS256');
            setcookie('refresh', $newRefreshToken, $this->expires(60 * 5), '', '', false, true);
            $this->returnResponse([
                'access_token' => $newAccessToken,
                'expired' => date(DATE_ATOM, $payload['exp']),
            ]);
        }
    }

    public function forgot_post()
    {
        $this->returnResponse("halo", "halo");
    }

    public function reset_post($token)
    {
        $this->returnResponse($token, "halo", 200);
    }

    public function profile_get()
    {
        try {
            $payload = $this->checkToken();
            $this->returnResponse($payload, "success");
        } catch (Exception $e) {
            $this->returnResponse(true, $e->getMessage(), 403);
        }
    }
}
