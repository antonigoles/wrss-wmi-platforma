<?php
    require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DatabaseConnection;

$connection = DatabaseConnection::get();

    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_user
        (
            usos_id INT PRIMARY KEY
        );",
        []
    );

    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_konkurs_prowadzacych
        (
            id INT AUTO_INCREMENT PRIMARY KEY,
            edycja VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );",
        []
    );

    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_kp_kategoria
        (
            id INT AUTO_INCREMENT PRIMARY KEY,
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
            id INT AUTO_INCREMENT PRIMARY KEY,
            kp_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            FOREIGN KEY (kp_id) REFERENCES t_konkurs_prowadzacych(id) ON DELETE CASCADE
        );",
        []
    );

    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_kt_vote
        (
            usos_id INT NOT NULL,
            kt_id INT NOT NULL,
            prowadzacy_id INT NOT NULL,
            voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            PRIMARY KEY (usos_id, kt_id),
            
            FOREIGN KEY (usos_id) REFERENCES t_user(usos_id) ON DELETE CASCADE,
            FOREIGN KEY (kt_id) REFERENCES t_poll(id) ON DELETE CASCADE,
            FOREIGN KEY (prowadzacy_id) REFERENCES t_kp_prowadzacy(id) ON DELETE CASCADE
        );",
        []
    );
?>