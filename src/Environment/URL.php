<?php

namespace App\Environment;

readonly class URL
{
    public function __construct(
        private string $path,
        private string $query,
    ) {}

    public function get_path(): string
    {
        return $this->path;
    }

    public function get_query(): string
    {
        return $this->query;
    }
}