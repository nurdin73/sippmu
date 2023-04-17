<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('oauth_model');
    }

    public function login_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');
        $clientId = $this->post('client_id');
        $clientSecret = $this->post('client_secret');
        if (!$username) $this->returnResponse(true, "Username Harus di isi", 422);
        if (!$password) $this->returnResponse(true, "Password Harus di isi", 422);
        if (!$clientId) $this->returnResponse(true, "Client ID Harus di isi", 422);
        if (!$clientSecret) $this->returnResponse(true, "Client secret Harus di isi", 422);
        try {
            $payload = [
                'username' => $username,
                'password' => $password,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ];

            $attempt = $this->oauth_model->attempt($payload);
            if ($attempt) {
                $accessToken = $this->oauth_model->setAccessToken($payload['client_id'], $attempt['id']);
                $refreshToken = $this->oauth_model->setRefreshToken($payload['client_id'], $attempt['id'], $accessToken['id']);
                // setcookie("refresh", $refreshToken, $this->expires(60 * 5), '', '', false, true);
                $this->returnResponse([
                    'access_token' => $accessToken['token'],
                    'refresh_token' => $refreshToken,
                    'expires_at' => date(DATE_ATOM, $accessToken['exp'])
                ]);
            }
            $this->returnResponse("Username atau password salah!", null, 403);
        } catch (Exception $e) {
            $this->returnResponse(true, $e->getMessage(), $e->getCode());
        }
    }

    public function refresh_post()
    {
        $token = $this->post('token');
        if (!$token) $this->returnResponse(true, "Token tidak ditemukan", 404);
        $decode = $this->oauth_model->decodeToken($token, 'refresh_token');
        if (!$decode) $this->returnResponse(true, "Token tidak ditemukan atau sudah kadaluarsa!", 404);
        $data = $this->oauth_model->getRefreshToken($decode['jti']);
        if (!$data) $this->returnResponse(true, "Token tidak ditemukan atau sudah kadaluarsa!", 404);
        $accessToken = $this->oauth_model->getAccessToken($data['access_token_id'], 'id');
        if (!$accessToken) $this->returnResponse(true, "Token tidak ditemukan atau sudah kadaluarsa!", 404);
        if (strtotime($accessToken['expires_at']) < time()) {
            $this->oauth_model->deleteAccessToken($accessToken['id']);
            $accessToken = $this->oauth_model->setAccessToken($accessToken['client_id'], $accessToken['user_id']);
            $refreshToken = $this->oauth_model->restoreRefreshToken($data['refresh_token'], $accessToken['id'], $data['client_id']);
            // setcookie("refresh", $refreshToken, $this->expires(60 * 5), '', '', false, true);
            $this->returnResponse([
                'access_token' => $accessToken['token'],
                'refresh_token' => $refreshToken,
                'expires_at' => date(DATE_ATOM, $accessToken['exp'])
            ]);
        } else {
            $this->returnResponse("Token masih aktif. Refresh token berhasil");
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
