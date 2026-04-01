<?php

namespace App\Secrets;

interface SecretsServiceInterface
{
    public function get(Secret $secret): int|string|bool|null;
}