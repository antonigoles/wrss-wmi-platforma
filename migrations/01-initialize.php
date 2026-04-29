<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DatabaseConnectionInterface;
use App\DependencyContainer;

$connection = DependencyContainer::get(DatabaseConnectionInterface::class);

$connection->query(
    "CREATE TABLE IF NOT EXISTS t_user
    (
        id SERIAL PRIMARY KEY,
        usos_id INT,

        UNIQUE (usos_id)
    );",
    []
);
?>