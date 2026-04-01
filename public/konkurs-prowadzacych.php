<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Authentication\Permissions\Permission;
use App\DependencyContainer;
use App\Website\Router\RouterService;

/** @var RouterService $router_service */
$router_service = DependencyContainer::get(RouterService::class);
$router_service->set_unauthenticated_redirect_page('login.php');
$router_service->require_permissions([ Permission::STUDENT_VOTE ]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRSS WMI</title>
    <link rel="stylesheet" href="/styles/style.main.css">
    <?php require_once('./styles/style-imports.php') ?>
</head>
<body>
<div class="global-container">
    <?php require_once('./components/header.php') ?>
    <div class="main-container">
    </div>
</div>
</body>
</html>