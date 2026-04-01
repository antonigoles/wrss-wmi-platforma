<?php

namespace App\Core\Environment;

interface EnvironmentInterface
{
    public function is_developer_mode(): bool;
    public function is_verbose_mode(): bool;
}