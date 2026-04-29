<?php

namespace App\Database;

use App\DependencyContainer;
use Exception;
use RuntimeException;

abstract class DatabaseModel
{
    private array $data; 

    protected function __construct(
        private string $table_name,
        private array $identifier_fields,
        private array|null $identifier_values_map,
        private bool $insert_identifiers = false
    ) 
    {
        if ($identifier_values_map !== null) {
            foreach ($identifier_values_map as $field => $value) {
                $this->data[$field] = $value;
            }
        }
    }

    private static function get_db_connection(): DatabaseConnectionInterface
    {
        $db_connection = DependencyContainer::get(DatabaseConnectionInterface::class);
        return $db_connection;
    }

    /**
     * @throws RuntimeException
     * @return array{'params:' mixed[], 'query:' string}
     */
    private function get_id_query(): array
    {
        // string of format: " field_1 = value_1 AND field_2 = value_2 AND ... "
        if ($this->identifier_values_map === null) {
            throw new RuntimeException("Can't calculate id guery for uninitialized model instance");
        }
        $params = [];
        $sub_queries = [];
        foreach ($this->identifier_values_map as $id_field => $id_value) {
            $sub_queries[] = "{$id_field} = ?";
            $params[] = $id_value;
        }

        return [
            'params' => $params,
            'query' => implode(" AND ", $sub_queries)
        ];
    }

    public function fetch(): self
    {
        $table_name = $this->table_name;
        $id_query = $this->get_id_query();
        $query = $id_query['query'];
        $this->data = $this->get_db_connection()->query(
            "SELECT * FROM {$table_name} WHERE {$query}", 
            $id_query['params']
        )[0];

        return $this;
    }

    public function exists(): bool
    {
        $table_name = $this->table_name;
        $id_query = $this->get_id_query();
        $query = $id_query['query'];
        $result = $this->get_db_connection()->query(
            "SELECT * FROM {$table_name} WHERE {$query}", 
            $id_query['params']
        );

        return !empty($result);
    }

    public function save(): static
    {
        if ($this->identifier_values_map === null || !$this->exists()) {
            $this->insert();
        } else {
            $this->update();
        }
        return $this;
    }

    protected function rebuild_identifier_values_map(): void
    {
        foreach ($this->identifier_fields as $field_name) {
            $this->identifier_values_map[$field_name] = $this->data[$field_name];
        }
    }

    private function insert(): void
    {
        $table_name = $this->table_name;
        $columns = [];
        $question_marks = [];
        $values = [];
        foreach ($this->data as $field_name => $field_value)
        {
            if (!$this->insert_identifiers) {
                if (in_array($field_name, $this->identifier_fields)) continue;
            }
            $columns[] = $field_name;
            $question_marks[] = "?";
            $values[] = $field_value;
        }

        $columns = implode(', ', $columns);
        $question_marks = implode(', ', $question_marks);
        
        $this->data = $this->get_db_connection()->query(
            "INSERT INTO {$table_name} ({$columns})
             VALUES ({$question_marks})
             RETURNING *", 
            $values
        )[0];

        $this->rebuild_identifier_values_map();
    }

    private function update(): void
    {
        $table_name = $this->table_name;
        $id_query = $this->get_id_query();
        $update_query = [];
        $params = [];
        foreach ($this->data as $field_name => $field_value)
        {
            if (in_array($field_name, $this->identifier_fields)) continue;
            $update_query[] = "{$field_name} = ?";
            $params[] = $field_value;
        }
        $update_query = implode(', ', $update_query);

        $params = array_merge($params, $id_query['params']);

        $this->data = $this->get_db_connection()->query(
            "UPDATE {$table_name} 
             SET {$update_query}
             WHERE {$id_query['query']} 
             RETURNING *", 
            $params
        )[0];
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function get(string $field_name): mixed
    {
        return $this->data[$field_name] ?? null;
    }

    public function set(string $field_name, mixed $value): static
    {
        $this->data[$field_name] = $value;
        return $this;
    }
}
?>