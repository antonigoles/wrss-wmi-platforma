<?php

namespace App\KonkursProwadzacych\Models;

use App\Database\DatabaseModel;

class Vote extends DatabaseModel
{
    public function __construct(?int $usos_id = null, ?int $kp_id = null, ?int $kategoria_id = null) {
        parent::__construct(
            't_kp_vote', 
            ['usos_id','kp_id', 'kategoria_id'], 
            $usos_id !== null && $kp_id !== null && $kategoria_id !== null ? 
            [
                'kategoria_id' => $kategoria_id,
                'usos_id'=> $usos_id, 
                'kp_id'=> $kp_id, 
            ] 
            : null,
            true
        );
    }

    public function get_konkurs_prowadzacych(): KonkursProwadzacych
    {
        return new KonkursProwadzacych($this->get('kp_id'));
    }

    public function set_konkurs_prowadzacych(int $konkurs_prowadzacych_id): self
    {
        return $this->set('kp_id', $konkurs_prowadzacych_id);
    }

    public function get_prowadzacy(): Prowadzacy
    {
        return new Prowadzacy($this->get('prowadzacy_id'));
    }

    public function set_prowadzacy(int $prowadzacy_id): self
    {
        return $this->set('prowadzacy_id', $prowadzacy_id);
    }

    public function get_kategoria(): Kategoria
    {
        return new Kategoria($this->get('kategoria_id'));
    }

    public function set_kategoria(int $kategoria_id): self
    {
        return $this->set('kategoria_id', $kategoria_id);
    }

    public function get_user(): mixed
    {
        return new Prowadzacy($this->get('usos_id'));
    }

    public function set_user(int $usos_id): self
    {
        return $this->set('usos_id', $usos_id);
    }

    public function get_voted_at(): mixed
    {
        return $this->get('voted_at');
    }

    /**
     * Make instance from database query result array
     * @return Vote
     */
    public static function from_array(array $data): Vote
    {
        $instance = new Vote();
        foreach ($data as $key => $value) {
            $instance->set($key, $value);
        }
        $instance->rebuild_identifier_values_map();
        return $instance;
    }
}