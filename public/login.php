<?php
require_once __DIR__ . '/../vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRSS WMI</title>
    <link rel="stylesheet" href="/styles/style.main.css">
    <?php require_once('./components/html-imports.php') ?>
</head>
<body>
    <div class="global-container">
        <?php require_once('./components/header.php') ?>
        <div class="main-container">
            <?php require_once('./components/login-with-usos.php') ?>
        </div>
    </div>
</body>
</html>