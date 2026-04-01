<?php

namespace App\USOS;

use App\Environment\Environment;
use App\Secrets\Secret;
use App\Secrets\SecretsServiceInterface;
use App\Session\SessionInterface;

;

class OAuthService implements OAuthServiceInterface
{
    const SESSION_TIMEOUT_TIME = 2 * 60 * 60; // 2 hours

    public function __construct(
        private readonly SessionInterface $session,
        private readonly Environment $environment,
        private readonly SecretsServiceInterface $secrets_service
    ) {
    }

    public function should_reauthenticate(): bool
    {
        $timestamp = $this->session->read('token_creation_timestamp');
        if (!$timestamp) {
            return true;
        }

        if (!is_numeric($timestamp)) {
            return true;
        }

        if (time() - intval($timestamp) >= self::SESSION_TIMEOUT_TIME) {
            return true;
        }

        return false;
    }

    public function fetch_user_data(): array|null
    {
        if ($this->should_reauthenticate()) return null;
        $user_data = $this->session->read('user_data');
        if ($user_data) {
            return $user_data;
        }

        $response = $this->send_oauth1_request(
            'GET',
            'https://usosapps.uwr.edu.pl/services/users/user',
            [
                'fields' => 'id|first_name|last_name'
            ],
            $_SESSION['oauth_token'],
            $_SESSION['oauth_token_secret']
        );

        $result = json_decode($response, true) ?? null;

        $this->session->write('user_data', $result);

        return $result;
    }

    public function fetch_user_id(): string|null
    {
        $data = $this->fetch_user_data();
        if (!isset($data['id'])) throw new \Exception('Auth error'); 
        return $data['id'];
    }

    public function request_token(string $callback_url): array
    {
        $response = $this->send_oauth1_request(
            'GET',
            'https://usosapps.uwr.edu.pl/services/oauth/request_token',
            [
                'oauth_callback' => $callback_url
            ]
        );

        $params = [];
        parse_str($response, $params);
        $params['original_response'] = $response;

        return $params;
    }

    public function request_access_token(string $oauth_token, string $oauth_token_secret, string $oauth_verifier): array
    {
        $response = $this->send_oauth1_request(
            'GET',
            "https://usosapps.uwr.edu.pl/services/oauth/access_token",
            [
                "oauth_verifier" => $oauth_verifier
            ],
            $oauth_token,
            $oauth_token_secret
        );

        $params = [];
        parse_str($response, $params);
        $params['original_response'] = $response;

        return $params;
    }

    public function revoke_token(string $auth_token): void
    {
        $this->send_oauth1_request(
            'GET',
            'https://usosapps.uwr.edu.pl/services/oauth/revoke_token',
            [],
            $auth_token
        );
    }

    private function send_oauth1_request($method, $url, $params, $token = '', $tokenSecret = ''): bool|string
    {
        $consumerKey = $this->secrets_service->get(Secret::USOS_CONSUMER_KEY);
        $consumerSecret = $this->secrets_service->get(Secret::USOS_CONSUMER_SECRET);

        $oauth = [
            'oauth_consumer_key' => $consumerKey,
            'oauth_nonce' => md5(mt_rand() . microtime()),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        ];

        if ($token) {
            $oauth['oauth_token'] = $token;
        }

        $allParams = array_merge($oauth, $params);

        ksort($allParams);

        $encodedParts = [];
        foreach ($allParams as $key => $value) {
            // RFC 3986
            $encodedParts[] = rawurlencode($key) . '=' . rawurlencode($value);
        }
        $parameterString = implode('&', $encodedParts);

        $baseString = strtoupper($method) . '&'
            . rawurlencode($url) . '&'
            . rawurlencode($parameterString);

        $signingKey = rawurlencode($consumerSecret) . '&' . rawurlencode($tokenSecret);

        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));

        $authHeaderParts = [];
        foreach ($oauth as $key => $value) {
            $authHeaderParts[] = $key . '="' . rawurlencode($value) . '"';
        }
        $header = 'Authorization: OAuth ' . implode(', ', $authHeaderParts);

        $ch = curl_init();

        if (strtoupper($method) === 'GET') {
            $requestUrl = $url . ($params ? '?' . http_build_query($params) : '');
            curl_setopt($ch, CURLOPT_URL, $requestUrl);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [$header, 'Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_SSL_VERIFYPEER => !$this->environment->is_developer_mode()
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Błąd cURL: ' . curl_error($ch));
        }

        return $response;
    }
}

?>