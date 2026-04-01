<?php

namespace App\Session;

interface SessionInterface
{
    public function write(string $key, mixed $value);

    public function read(string $key): mixed;

    public function has(string $key): bool;

    public function remove(string $key): void;

    public function clear(): void;
}

?>