<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DatabaseConnectionInterface;
use App\DependencyContainer;

$connection = DependencyContainer::get(DatabaseConnectionInterface::class);

$connection->query(
    "CREATE TABLE IF NOT EXISTS t_konkurs_prowadzacych
    (
        id SERIAL PRIMARY KEY,
        edycja VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );",
    []
);

$connection->query(
    "CREATE TABLE IF NOT EXISTS t_kp_kategoria
    (
        id SERIAL PRIMARY KEY,
        kp_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        FOREIGN KEY (kp_id) REFERENCES t_konkurs_prowadzacych(id) ON DELETE CASCADE
    );",
    []
);

$connection->query(
    "CREATE TABLE IF NOT EXISTS t_kp_prowadzacy
    (
        id SERIAL PRIMARY KEY,
        kp_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        FOREIGN KEY (kp_id) REFERENCES t_konkurs_prowadzacych(id) ON DELETE CASCADE
    );",
    []
);

$connection->query(
    "CREATE TABLE IF NOT EXISTS t_kp_vote
    (
        usos_id INT NOT NULL,
        kp_id INT NOT NULL,
        kategoria_id INT NOT NULL,
        prowadzacy_id INT NOT NULL,
        voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        PRIMARY KEY (usos_id, kategoria_id, kp_id),
        
        FOREIGN KEY (usos_id) REFERENCES t_user(usos_id) ON DELETE CASCADE,
        FOREIGN KEY (kp_id) REFERENCES t_konkurs_prowadzacych(id) ON DELETE CASCADE,
        FOREIGN KEY (prowadzacy_id) REFERENCES t_kp_prowadzacy(id) ON DELETE CASCADE,
        FOREIGN KEY (kategoria_id) REFERENCES t_kp_kategoria(id) ON DELETE CASCADE
    );",
    []
);
?>