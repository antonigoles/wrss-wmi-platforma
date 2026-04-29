<?php

namespace App\User;

use App\Database\DatabaseModel;

class User extends DatabaseModel
{
    public function __construct(?int $id = null) {
        parent::__construct('t_user', ['id'], $id ? [ 'id'=> $id ] : null);
    }

    public function get_usos_id(): ?int
    {
        return $this->get('usos_id');
    }

    public function set_usos_id(int $usos_id): self
    {
        return $this->set('usos_id', $usos_id);
    }
}

?>