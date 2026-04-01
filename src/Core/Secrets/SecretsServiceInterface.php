<?php

namespace App\Core\Secrets;

interface SecretsServiceInterface
{
    public function get(Secret $secret): int|string|bool|null;
}