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
echo $environment->get_current_path();
?>