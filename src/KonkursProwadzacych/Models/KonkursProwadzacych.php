<?php

namespace App\KonkursProwadzacych\Models;

use App\Database\DatabaseModel;

class KonkursProwadzacych extends DatabaseModel
{
    public function __construct(?int $id = null) {
        parent::__construct('t_konkurs_prowadzacych', ['id'], $id !== null ? [ 'id'=> $id ] : null);
    }

    public function get_id(): ?int
    {
        return $this->get('id');
    }

    public function set_edycja(string $edycja): self
    {
        return $this->set('edycja', $edycja);
    }

    public function get_edycja(): ?string
    {
        return $this->get('edycja');
    }

    public function get_created_at(): ?int
    {
        return $this->get('get_created_at');
    }

    /**
     * Make instance from database query result array
     * @return KonkursProwadzacych
     */
    public static function from_array(array $data): KonkursProwadzacych
    {
        $instance = new KonkursProwadzacych();
        foreach ($data as $key => $value) {
            $instance->set($key, $value);
        }
        $instance->rebuild_identifier_values_map();
        return $instance;
    }
}