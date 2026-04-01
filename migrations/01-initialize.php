<?php
    require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DatabaseConnection;

$connection = DatabaseConnection::get();

    // 1. Tabela Użytkowników
    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_user
        (
            usos_id INT PRIMARY KEY
        );",
        []
    );

    // 2. Tabela Ankiet
    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_poll
        (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );",
        []
    );

    // 3. Tabela Opcji w Ankiecie
    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_poll_option
        (
            id INT AUTO_INCREMENT PRIMARY KEY,
            poll_id INT NOT NULL,
            label VARCHAR(255) NOT NULL,
            FOREIGN KEY (poll_id) REFERENCES t_poll(id) ON DELETE CASCADE
        );",
        []
    );

    // 4. Tabela Głosów Użytkowników
    $connection->query(
        "CREATE TABLE IF NOT EXISTS t_poll_user_vote
        (
            usos_id INT NOT NULL,
            poll_id INT NOT NULL,
            poll_option_id INT NOT NULL,
            voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            -- Gwarantuje, że dany użytkownik może zagłosować tylko raz w danej ankiecie
            PRIMARY KEY (usos_id, poll_id),
            
            -- Klucze obce
            FOREIGN KEY (usos_id) REFERENCES t_user(usos_id) ON DELETE CASCADE,
            FOREIGN KEY (poll_id) REFERENCES t_poll(id) ON DELETE CASCADE,
            FOREIGN KEY (poll_option_id) REFERENCES t_poll_option(id) ON DELETE CASCADE
        );",
        []
    );
?>