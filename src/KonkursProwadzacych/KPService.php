<?php

namespace App\KonkursProwadzacych;

use App\Database\DatabaseConnectionInterface;
use App\KonkursProwadzacych\Aggregates\UserKonkursProwadzacychAggregate;
use App\KonkursProwadzacych\Models\Kategoria;
use App\KonkursProwadzacych\Models\Prowadzacy;
use App\KonkursProwadzacych\Models\Vote;
use App\Utilities\ArrayUtilities;

class KPService
{
    public function __construct(
        private KPRepository $kp_repository
    ) {}

    /**
     * @return UserKonkursProwadzacychAggregate[]
     */
    public function get_all_voting_page_data_for_user(int $usos_id): array {
        return $this->kp_repository->get_all_voting_page_data_for_user($usos_id);
    }

    public function get_all_konkurs_prowadzacych_data_for_user(int $usos_id, int $konkurs_id): ?UserKonkursProwadzacychAggregate {
        return $this->kp_repository->get_all_konkurs_prowadzacych_data_for_user($usos_id, $konkurs_id);
    }

    public function upsert_vote(int $usos_id, int $konkurs_id, int $prowadzacy_id, int $kategoria_id): Vote
    {
        $vote = new Vote(
            $usos_id, 
            $konkurs_id, 
            $kategoria_id
        );
        
        if ($vote->exists()) {
            $vote->fetch();
        } 

        $vote->set_prowadzacy($prowadzacy_id);
        $vote->save();
        return $vote;
    }

    /**
     * Returns response
     * @param int $usos_id
     * @param int $konkurs_id
     * @param array $votes
     * @return array{error: string|null}
     */
    public function commit_votes(int $usos_id, int $konkurs_id, array $votes): array
    {
        if (count($votes) > 20) {
            return [
                "error" => "Nie można zcommitować więcej niż 20 głosów na raz"
            ];
        }

        $konkurs_data = $this->get_all_konkurs_prowadzacych_data_for_user($usos_id, $konkurs_id);
        if ($konkurs_data === null) {
            return [
                "error" => "Ten konkurs nie istnieje"
            ];
        }

        // 1. Validate everything first
        foreach ($votes as $kategoria_id => $prowadzacy_id) {
            // 1. Check if this kategoria_id exists
            $search_result = ArrayUtilities::array_has(
                $konkurs_data->get_kategorie(),
                function (Kategoria $kategoria) use ($kategoria_id) {
                    return $kategoria->get_id() === $kategoria_id;
                }
            );

            if (!$search_result) {
                return [
                    "error" => "Kategoria nie istnieje"
                ];
            }

            // 2. Check if this prowadzacy_id exists
            $search_result = ArrayUtilities::array_has(
                $konkurs_data->get_prowadzacy(),
                function (Prowadzacy $prowadzacy) use ($prowadzacy_id) {
                    return $prowadzacy->get_id() === $prowadzacy_id;
                }
            );

            if (!$search_result) {
                return [
                    "error" => "Prowadzacy nie istnieje"
                ];
            }
        }

        foreach ($votes as $kategoria_id => $prowadzacy_id) {
            $vote = $this->upsert_vote($usos_id, $konkurs_id, $prowadzacy_id ,$kategoria_id);
        }

        return [
            "success" => "woohoo"
        ];
    }
}