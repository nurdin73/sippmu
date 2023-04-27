<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Oauth_model extends CI_Model
{
    const OAUTH_CLIENT = 'oauth_clients';
    const OAUTH_ACCESS_TOKEN = 'oauth_access_tokens';
    const OAUTH_REFRESH_TOKEN = 'oauth_refresh_tokens';
    const USER_PERMISSION = 'm_user_permissions';
    const USERS = 'users';
    const SDM = 'm_sdm';

    public function __construct()
    {
        parent::__construct();
    }

    protected function expires($additional = 0)
    {
        return time() + (env('EXPIRED_TOKEN') + $additional);
    }

    public function getAll()
    {
        $this->datatables->select("client_id as id, client_name as nama, client_secret, redirect_uri");
        $this->datatables->from(self::OAUTH_CLIENT);
        $this->datatables->add_column('view', '<a data-id="$1" class="btn btn-default btn-xs btn-edit" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs btn-delete" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,nama');
        return $this->datatables->generate();
    }

    public function getAllClients()
    {
        $query = $this->db->get(self::OAUTH_CLIENT);
        return $query->result_array();
    }

    public function addPermissionClient($clientId, $userId)
    {
        $this->db->set('user_id', $userId);
        $this->db->set('client_id', $clientId);
        $this->db->insert(self::USER_PERMISSION);
    }

    public function removePermissionClientByUserId($userId)
    {
        $this->db->where('user_id', $userId);
        $this->db->delete(self::USER_PERMISSION);
    }

    public function getPermissionClientByuserId($userId)
    {
        $this->db->where('user_id', $userId);
        $query = $this->db->get(self::USER_PERMISSION);
        return $query->result_array();
    }

    public function get($id)
    {
        $this->db->where('client_id', $id);
        $query = $this->db->get(self::OAUTH_CLIENT);
        return $query->row_array();
    }

    public function create($data)
    {
        $this->db->insert(self::OAUTH_CLIENT, $data);
        $clientId = $this->db->insert_id();
        return $clientId;
    }

    public function update($id, $data)
    {
        $this->db->where('client_id', $id);
        $this->db->update(self::OAUTH_CLIENT, $data);
    }

    public function destroy($id)
    {
        $this->db->where('client_id', $id);
        $this->db->delete(self::OAUTH_CLIENT);
    }

    /**
     * check client credentials
     *
     * @param integer $clientId
     * @param string $clientSecret
     * @return boolean
     */
    public function checkClientCredential(int $clientId, string $clientSecret): bool
    {
        $this->db->from(self::OAUTH_CLIENT);
        $this->db->where('client_id', $clientId);
        $this->db->where('client_secret', $clientSecret);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * check permission user
     *
     * @param integer $clientId
     * @param integer $userId
     * @return boolean
     */
    public function checkPermission(int $clientId, int $userId)
    {
        $this->db->from(self::USER_PERMISSION);
        $this->db->where('client_id', $clientId);
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        // return $query->row_array();

        if ($query->num_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * check is sdm
     *
     * @param integer $userId
     * @return boolean
     */
    public function checkIsSdm(int $userId): bool
    {
        $this->db->from(self::SDM);
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        // return $query->row_array();

        if ($query->num_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * check user
     *
     * @param array $credential
     * @return array|boolean
     */
    public function checkUser(array $credential)
    {
        $this->db->from(self::USERS);
        $this->db->where('email', $credential['username']);
        $this->db->or_where('username', $credential['username']);
        $this->db->or_where('nbm', $credential['username']);
        $query = $this->db->get();
        $user = $query->row_array();
        if (password_verify($credential['password'], $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * check user attempt
     *
     * @param array $cred
     * @return array|Exception
     */
    public function attempt(array $cred)
    {
        // check credential client
        if (!$this->checkClientCredential($cred['client_id'], $cred['client_secret'])) throw new Exception("Client Credential not match!", 404);
        // check user credential
        $user = $this->checkUser($cred);
        if (!$user) throw new Exception("Username or password not match!", 404);
        if (!$this->checkIsSdm($user['id'])) throw new Exception("User is not registered as SDM", 404);
        // check permissions credential
        if (!$this->checkPermission($cred['client_id'], $user['id'])) throw new Exception("User dont have permission!", 403);
        unset($user['password']);
        return $user;
    }

    public function setAccessToken(int $clientId, int $userId)
    {
        $tokenPayload['exp'] = $this->expires();
        $tokenPayload['aud'] = $clientId;
        $payload['access_token'] = randomStr(60);
        $payload['user_id'] = $userId;
        $payload['client_id'] = $clientId;
        $payload['expires_at'] = date(DATE_ATOM, $tokenPayload['exp']);
        $this->db->insert(self::OAUTH_ACCESS_TOKEN, $payload);
        $tokenId = $this->db->insert_id();
        $tokenPayload['jti'] = $payload['access_token'];
        $token = JWT::encode($tokenPayload, env('ACCESS_TOKEN_SECRET'), 'HS256');
        return [
            'id' => $tokenId,
            'token' => $token,
            'exp' => $tokenPayload['exp']
        ];
    }

    public function getAccessToken($id, $by = 'id')
    {
        $this->db->where($by, $id);
        $query = $this->db->get(self::OAUTH_ACCESS_TOKEN);
        return $query->row_array();
    }

    public function deleteAccessToken($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(self::OAUTH_ACCESS_TOKEN);
    }

    public function setRefreshToken($clientId, $userId, $accessTokenId)
    {
        $tokenPayload['exp'] = $this->expires(60 * 15);
        $tokenPayload['aud'] = $clientId;
        $payload['refresh_token'] = randomStr(60);
        $payload['access_token_id'] = $accessTokenId;
        $payload['client_id'] = $clientId;
        $payload['user_id'] = $userId;
        $this->db->insert(self::OAUTH_REFRESH_TOKEN, $payload);
        $tokenId = $this->db->insert_id();
        $tokenPayload['jti'] = $payload['refresh_token'];
        $token = base64_encode(json_encode($tokenPayload));
        return $token;
    }

    public function restoreRefreshToken($token, $accessTokenId, $clientId)
    {
        $this->db->where('refresh_token', $token);
        $this->db->update(self::OAUTH_REFRESH_TOKEN, [
            'access_token_id' => $accessTokenId
        ]);
        $tokenPayload['exp'] = $this->expires(60 * 15);
        $tokenPayload['aud'] = $clientId;
        $tokenPayload['jti'] = $token;
        $token = base64_encode(json_encode($tokenPayload));
        return $token;
    }

    public function getRefreshToken($token)
    {
        $this->db->where('refresh_token', $token);
        $query = $this->db->get(self::OAUTH_REFRESH_TOKEN);
        return $query->row_array();
    }

    public function decodeToken(string $token, string $type = 'access_token')
    {
        if ($type == 'access_token') {
            return JWT::decode($token, new Key(env('ACCESS_TOKEN_SECRET'), 'HS256'));
        } else {
            return json_decode(base64_decode($token), true);
        }
    }
}
