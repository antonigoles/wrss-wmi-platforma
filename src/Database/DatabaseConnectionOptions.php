<?php

namespace App\Database;

class DatabaseConnectionOptions
{
    public function __construct(
        private string $host = 'db',
        private string $port = '5432',
        private string $database = 'main',
        private string $username = 'root',
        private string $password = 'abc_root_123'
    ) {}

    public function get_host(): string
    {
        return $this->host;
    }

    public function get_port(): string
    {
        return $this->port;
    }

    public function get_database(): string
    {
        return $this->database;
    }

    public function get_username(): string
    {
        return $this->username;
    }

    public function get_password(): string
    {
        return $this->password;
    }
}