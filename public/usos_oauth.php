<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\DependencyContainer;
use App\Environment\EnvironmentInterface;
use App\Secrets\Secret;
use App\Secrets\SecretsServiceInterface;
use App\Session\SessionInterface;
use App\USOS\OAuthServiceInterface;
use App\Website\WebsiteUtilities;

/** @var OAuthServiceInterface $oauth_service */
$oauth_service = DependencyContainer::get(OAuthServiceInterface::class);

/** @var SecretsServiceInterface $secrets_service */
$secrets_service = DependencyContainer::get(SecretsServiceInterface::class);

/** @var SessionInterface $session */
$session = DependencyContainer::get(SessionInterface::class);

/** @var EnvironmentInterface $environment */
$environment = DependencyContainer::get(EnvironmentInterface::class);

if (!isset($_GET['oauth_token']) || !isset($_GET['oauth_verifier'])) {
    $next_url = WebsiteUtilities::sanitize_redirect_url($_GET['next'] ?? '/');

    $response = $oauth_service->request_token(
        $secrets_service->get(Secret::APP_URL).'/usos_oauth.php?next=' . rawurldecode($next_url)
    );

    if (!isset($response['oauth_token']) || !isset($response['oauth_token_secret'])) {
        echo 'Niepoprawna odpowiedź z USOSa. Spróbuj później (Stage 1)';
        if ($environment->is_verbose_mode()) {
            echo json_encode($response, JSON_PRETTY_PRINT) . "<br>";
        }
        die();
    }

    $session->write('oauth_token', $response['oauth_token']);
    $session->write('oauth_token_secret', $response['oauth_token_secret']);


    // redirect to authorize page
    $params = http_build_query($response);
    $authorize_url = "https://usosapps.uwr.edu.pl/services/oauth/authorize?$params";
    header("Location: $authorize_url");
    die();
} else {
    $oauth_token = $session->read('oauth_token');;
    $oauth_token_secret = $session->read('oauth_token_secret');

    if (!$oauth_token || !$oauth_token_secret) {
        header("Location: /");
        die();
    }

    if ($oauth_token !== $_GET['oauth_token']) {
        header("Location: /");
        die();
    }

    // ok now let's get the access token
    $response = $oauth_service->request_access_token(
        $oauth_token,
        $oauth_token_secret,
        $_GET['oauth_verifier']
    );

    if (!isset($response['oauth_token']) || !isset($response['oauth_token_secret'])) {
        echo 'Niepoprawna odpowiedź z USOSa. Spróbuj później (Stage 2)';
        if ($environment->is_verbose_mode()) {
            var_dump($response);
        }
        die();
    }

    $session->write('token_creation_timestamp', time());
    $session->write('oauth_token', $response['oauth_token']);
    $session->write('oauth_token_secret', $response['oauth_token_secret']);

    $base_app_url = $secrets_service->get(Secret::APP_URL);
    $return_url = $_GET['next'] ?? $base_app_url;
    $session->remove('return_url');
    $return_url = WebsiteUtilities::sanitize_redirect_url($return_url);
    header("Location: $return_url");
    die();
}

?>