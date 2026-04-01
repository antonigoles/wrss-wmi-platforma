<?php
    require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\DependencyInjection\DependencyContainer;
use App\USOS\OAuthService;
use App\USOS\OAuthServiceInterface;

/** @var OAuthServiceInterface $oauth_service */
$oauth_service = DependencyContainer::get(OAuthServiceInterface::class);

$should_reauthenticate = $oauth_service->should_reauthenticate();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Co twoim zdanime było trudniejsze?</title>
    <link rel="stylesheet" href="/styles/style.main.css">
    <?php require_once('./styles/style-imports.php') ?>
</head>
<body>
    <div class="global-container">
        <div class="page-header">
            <?php 
                !$should_reauthenticate && require('./components/logout-button.php')
            ?>
        </div>

        <div class="main-container">
        <?php 
            $should_reauthenticate ? 
                require('./components/login-with-usos.php') :
                require('./components/home-screen.php')
        ?>
        </div>
    </div>
</body>
</html>