<?php

use App\Core\DependencyInjection\DependencyContainer;
use App\Core\Secrets\Secret;
use App\Core\Secrets\SecretsServiceInterface;
use App\Core\Session\SessionInterface;
use App\USOS\OAuthServiceInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var OAuthServiceInterface $oauth_service */
$oauth_service = DependencyContainer::get(OAuthServiceInterface::class);

/** @var SecretsServiceInterface $secrets_service */
$secrets_service = DependencyContainer::get(SecretsServiceInterface::class);

/** @var SessionInterface $session */
$session = DependencyContainer::get(SessionInterface::class);

if (!$oauth_service->should_reauthenticate()) {
    $oauth_service->revoke_token($session->read('oauth_token'));
}

$session->clear();
$base_app_url = $secrets_service->get(Secret::APP_URL);
header("Location: $base_app_url");
die();
?>