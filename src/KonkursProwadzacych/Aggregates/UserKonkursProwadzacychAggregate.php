<?php

namespace App\KonkursProwadzacych\Aggregates;

use App\Database\DatabaseModel;
use App\KonkursProwadzacych\Models\Kategoria;
use App\KonkursProwadzacych\Models\KonkursProwadzacych;
use App\KonkursProwadzacych\Models\Prowadzacy;
use App\KonkursProwadzacych\Models\Vote;

class UserKonkursProwadzacychAggregate
{
    /**
     * Summary of __construct
     * @param KonkursProwadzacych $konkurs
     * @param Prowadzacy[] $prowadzacy
     * @param Kategoria[] $kategorie
     * @prama Vote[] $user_votes
     */
    public function __construct(
        private KonkursProwadzacych $konkurs,
        private array $prowadzacy,
        private array $kategorie,
        private array $user_votes
    ) {
    }

    public function get_konkurs(): KonkursProwadzacych
    {
        return $this->konkurs;
    }

    /**
     * @return Prowadzacy[]
     */
    public function get_prowadzacy(): array
    {
        return $this->prowadzacy;
    }

    /**
     * @return Kategoria[]
     */
    public function get_kategorie(): array
    {
        return $this->kategorie;
    }

    /**
     * Summary of get_votes
     * @return array
     */
    public function get_votes(): array
    {
        return $this->user_votes;
    }

    public function to_array(): array
    {
        return [
           "konkurs" => $this->konkurs->get_data(),
           "prowadzacy" => array_map(static fn (Prowadzacy $p) => $p->get_data(), $this->prowadzacy),
           "kategorie" => array_map(static fn (Kategoria $k) => $k->get_data(), $this->kategorie),
           "votes" => array_map(static fn (Vote $v) => $v->get_data(), $this->user_votes),
        ];
    }
}