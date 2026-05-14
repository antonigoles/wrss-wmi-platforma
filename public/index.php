<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Authentication\AuthenticationServiceInterface;
use App\Authentication\Permissions\Permission;
use App\DependencyContainer;
use App\Website\Router\RouterService;

/** @var RouterService $router_service */
$router_service = DependencyContainer::get(RouterService::class);
$router_service->require_permissions([ Permission::DISPLAY_HOME_PAGE ]);

/** @var AuthenticationServiceInterface $auth_serivce */
$auth_serivce = DependencyContainer::get(AuthenticationServiceInterface::class);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRSS WMI</title>
    <link rel="stylesheet" href="/styles/style.main.css">
    <link rel="icon" type="image/x-icon" href="/assets/wrsslogo.ico">
    <?php require_once('./components/html-imports.php') ?>

</head>
<script>
    function is_logged_in() {
        return <?php echo $auth_serivce->is_logged_in() ? 'true' : 'false'; ?>;
    }
</script>
<body>
    <div class="wrss-logo"></div>
    <div class="global-container">
        <?php require_once('./components/article.php') ?>
    </div>
</body>
<script src="/scripts/showdown/showdown.min.js"></script>
</html>