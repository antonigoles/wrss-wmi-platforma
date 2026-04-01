<?php

namespace App\USOS;

use App\Core\DependencyInjection\DependencyContainer;
use App\Core\Environment\Environment;
use App\Core\Secrets\Secret;
use App\Core\Secrets\SecretsServiceInterface;
use App\Core\Session\SessionInterface;

;

interface OAuthServiceInterface
{
    public function should_reauthenticate(): bool;

    public function fetch_user_data(): array|null;

    public function fetch_user_id(): string|null;

    public function request_token(string $callback_url): array;

    public function revoke_token(string $auth_token): void;

    public function request_access_token(string $oauth_token, string $oauth_token_secret, string $oauth_verifier): array;
}

?>