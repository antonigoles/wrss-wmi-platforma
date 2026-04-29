<?php

namespace App\User;

use App\Database\DatabaseConnectionInterface;
use App\USOS\OAuthServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        public DatabaseConnectionInterface $db_connection
    ) {}

    public function get_user_from_usos_id(int $usos_id): User
    {
        $result = $this->db_connection->query(
            'SELECT id FROM t_user WHERE usos_id = ?', 
            [$usos_id]
        );
        if (!empty($result)) {
            $id = $result[0]['id'];
            return (new User($id))->fetch();
        } 
        return (new User())
            ->set_usos_id($usos_id)
            ->save();
    }
}

?>