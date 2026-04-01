<?php

namespace App\Database;

class DatabaseConnection implements DatabaseConnectionInterface
{
    private \PDO $pdo;

    public function __construct(
        private readonly DatabaseConnectionOptions $connection_options
    ) {
        $this->initialize_connection();
    }

    private function initialize_connection(): void
    {
        $connection_string = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s",
            $this->connection_options->get_host(),
            $this->connection_options->get_port(),
            $this->connection_options->get_database()
        );

        $this->pdo = new \PDO(
            $connection_string,
            $this->connection_options->get_username(),
            $this->connection_options->get_password()
        );
    }

    /**
     * Make any query
     * @param string $query
     * @param array $params
     * @return array
     */
    public function query(string $query, array $params): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Run query and retrieve specific field as a list
     * @param string $query
     * @param array $params
     * @return array
     */
    public function query_field(string $query, array $params, string $field): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return array_map(
            function ($row) use ($field) { 
                return $row[$field];
            }, 
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    public function get_last_inserted_id(): string|bool
    {
        return $this->pdo->lastInsertId();
    }
}

?>