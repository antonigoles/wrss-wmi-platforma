<?php

namespace App\KonkursProwadzacych;

use App\Database\DatabaseConnectionInterface;
use App\KonkursProwadzacych\Aggregates\UserKonkursProwadzacychAggregate;
use App\KonkursProwadzacych\Models\Kategoria;
use App\KonkursProwadzacych\Models\KonkursProwadzacych;
use App\KonkursProwadzacych\Models\Prowadzacy;
use App\KonkursProwadzacych\Models\Vote;

class KPRepository
{
    public function __construct(
        private DatabaseConnectionInterface $db_connection
    ) {}

    /**
     * Summary of get_all
     * @return UserKonkursProwadzacychAggregate[]
     */
    public function get_all_voting_page_data_for_user(int $usos_id): array {
        $query_result = $this->db_connection->query('SELECT * FROM t_konkurs_prowadzacych', []);
        $konkursy = array_map(static fn (array $data) => KonkursProwadzacych::from_array($data), $query_result);
        $result = [];
        foreach ($konkursy as $konkurs) {
            $kategorie_result = $this->db_connection->query('SELECT * FROM t_kp_kategoria WHERE kp_id = ?', [$konkurs->get_id()]);
            $kategorie = array_map(static fn (array $data) => Kategoria::from_array($data), $kategorie_result);
            
            $prowadzacy_result = $this->db_connection->query('SELECT * FROM t_kp_prowadzacy WHERE kp_id = ?', [$konkurs->get_id()]);
            $prowadzacy = array_map(static fn (array $data) => Prowadzacy::from_array($data), $prowadzacy_result);

            $votes_result = $this->db_connection->query('SELECT * FROM t_kp_vote WHERE kp_id = ? AND usos_id = ?', [$konkurs->get_id(), $usos_id]);
            $votes = array_map(static fn (array $data) => Vote::from_array($data), $votes_result);
            
            $result[] = new UserKonkursProwadzacychAggregate(
                konkurs: $konkurs,
                prowadzacy: $prowadzacy,
                kategorie: $kategorie,
                user_votes: $votes
            );
        }
        return $result;
    }

    public function get_all_konkurs_prowadzacych_data_for_user(int $usos_id, int $konkurs_id): ?UserKonkursProwadzacychAggregate
    {
        $query_result = $this->db_connection->query('SELECT * FROM t_konkurs_prowadzacych WHERE id = ?', [$konkurs_id]);
        if (empty($query_result)) return null;
        $query_result = $query_result[0];
        $konkurs = KonkursProwadzacych::from_array($query_result);
        
        $kategorie_result = $this->db_connection->query('SELECT * FROM t_kp_kategoria WHERE kp_id = ?', [$konkurs->get_id()]);
        $kategorie = array_map(static fn (array $data) => Kategoria::from_array($data), $kategorie_result);
        
        $prowadzacy_result = $this->db_connection->query('SELECT * FROM t_kp_prowadzacy WHERE kp_id = ?', [$konkurs->get_id()]);
        $prowadzacy = array_map(static fn (array $data) => Prowadzacy::from_array($data), $prowadzacy_result);

        $votes_result = $this->db_connection->query('SELECT * FROM t_kp_vote WHERE kp_id = ? AND usos_id = ?', [$konkurs->get_id(), $usos_id]);
        $votes = array_map(static fn (array $data) => Vote::from_array($data), $votes_result);
        
        return new UserKonkursProwadzacychAggregate(
            konkurs: $konkurs,
            prowadzacy: $prowadzacy,
            kategorie: $kategorie,
            user_votes: $votes
        );;
    }

    /**
     * @return int[]
     */
    public function get_all_user_votes(int $usos_id): array
    {
        $query_result = $this->db_connection->query('SELECT * FROM t_kp_vote WHERE usos_id = ?', [ $usos_id ]);
        return array_map(static fn (array $data) => Vote::from_array($data), $query_result);
    }
}