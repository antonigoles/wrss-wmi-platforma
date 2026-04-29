<?php

namespace App\KonkursProwadzacych\Models;

use App\Database\DatabaseModel;

class Kategoria extends DatabaseModel
{
    public function __construct(?int $id = null) {
        parent::__construct('t_kp_kategoria', ['id'], $id !== null ? [ 'id'=> $id ] : null);
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

    public function get_description(): string
    {
        return $this->get('description');
    }

    public function set_description(string $description): self
    {
        return $this->set('description', $description);
    }

    public static function from_array(array $data): Kategoria
    {
        $instance = new Kategoria();
        foreach ($data as $key => $value) {
            $instance->set($key, $value);
        }
        $instance->rebuild_identifier_values_map();
        return $instance;
    }
}