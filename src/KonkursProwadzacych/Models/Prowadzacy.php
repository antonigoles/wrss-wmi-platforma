<?php

namespace App\KonkursProwadzacych\Models;

use App\Database\DatabaseModel;

class Prowadzacy extends DatabaseModel
{
    public function __construct(?int $id = null) {
        parent::__construct('t_kp_prowadzacy', ['id'], $id !== null ? [ 'id'=> $id ] : null);
    }

    public function get_id(): int
    {
        return $this->get('id');
    }

    public function get_konkurs_prowadzacych(): KonkursProwadzacych
    {
        return new KonkursProwadzacych($this->get('kp_id'));
    }

    public function set_konkurs_prowadzacych(int $konkurs_prowadzacych_id): self
    {
        return $this->set('kp_id', $konkurs_prowadzacych_id);
    }

    public function get_name(): string
    {
        return $this->get('name');
    }

    public function set_name(string $name): self
    {
        return $this->set('name', $name);
    }

    public static function from_array(array $data): Prowadzacy
    {
        $instance = new Prowadzacy();
        foreach ($data as $key => $value) {
            $instance->set($key, $value);
        }
        $instance->rebuild_identifier_values_map();
        return $instance;
    }
}