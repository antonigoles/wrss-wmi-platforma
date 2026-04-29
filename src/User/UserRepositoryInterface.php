<?php

namespace App\User;

interface UserRepositoryInterface
{
    /**
     * This method will either return existing user from database or create a new one
     * @param int $usos_id
     * @return void
     */
    public function get_user_from_usos_id(int $usos_id): User;
}

?>