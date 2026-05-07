<?php

namespace App\Environment;

use App\Secrets\Secret;
use App\Secrets\SecretsServiceInterface;

readonly class Environment implements EnvironmentInterface
{
    public function __construct(
        private SecretsServiceInterface $secrets_service,
    ) {}

    public function is_developer_mode(): bool
    {
        return $this->secrets_service->get(Secret::DEVELOPER_MODE);
    }

    public function is_verbose_mode(): bool
    {
        return $this->secrets_service->get(Secret::VERBOSE_MODE);
    }

    public function get_app_url(): string
    {
        return $this->secrets_service->get(Secret::APP_URL);
    }

    public function get_current_path(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function get_unparsed_current_url(): string
    {
        $http = $this->is_developer_mode() ? "http" : "https";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        return $http . "://" . $host . $uri;
    }

    public function get_current_url(): URL
    {
        $parsed = parse_url($this->get_unparsed_current_url());
        return new URL(
            path: $parsed['path'] ?? '',
            query: $parsed['query'] ?? ''
        );
    }
}