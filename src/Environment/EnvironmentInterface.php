<?php

namespace App\Environment;

interface EnvironmentInterface
{
    public function is_developer_mode(): bool;
    public function is_verbose_mode(): bool;
    public function get_app_url(): string;
    public function get_current_path(): string;
    public function get_unparsed_current_url(): string;
    public function get_current_url(): URL;
}