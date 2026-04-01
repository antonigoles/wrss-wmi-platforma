<?php

use App\Core\DependencyInjection\DependencyContainer;
use App\Core\Environment\EnvironmentInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var EnvironmentInterface $environment */
$environment = DependencyContainer::get(EnvironmentInterface::class);

if (!$environment->is_developer_mode()) {
    echo ":(";
    die();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($_SESSION, JSON_PRETTY_PRINT);
?>