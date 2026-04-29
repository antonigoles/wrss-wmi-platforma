<?php

use App\Authentication\AuthenticationServiceInterface;
use App\DependencyContainer;
use App\Environment\EnvironmentInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var EnvironmentInterface $environment */
$environment = DependencyContainer::get(EnvironmentInterface::class);

/** @var AuthenticationServiceInterface $auth_service */
$auth_service = DependencyContainer::get(AuthenticationServiceInterface::class);

if (!$environment->is_developer_mode()) {
    echo ":(";
    die();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($auth_service->get_current_session_user()->get_data(), JSON_PRETTY_PRINT);
?>