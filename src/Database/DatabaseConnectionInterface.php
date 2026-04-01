<?php

namespace App\Database;

interface DatabaseConnectionInterface
{
    /**
     * Make any query
     * @param string $query
     * @param array $params
     * @return array
     */
    public function query(string $query, array $params): array;


    /**
     * Run query and retrieve specific field as a list
     * @param string $query
     * @param array $params
     * @param string $field
     * @return array
     */
    public function query_field(string $query, array $params, string $field): array;

    public function get_last_inserted_id(): string|bool;
}

?>