<?php

namespace App\Core\Environment;

use App\Core\DependencyInjection\DependencyContainer;
use App\Core\Secrets\Secret;
use App\Core\Secrets\SecretsServiceInterface;

class Environment implements EnvironmentInterface
{
    public function __construct(
        private readonly SecretsServiceInterface $secrets_service,
    ) {}

    public function is_developer_mode(): bool
    {
        return $this->secrets_service->get(Secret::DEVELOPER_MODE);
    }

    public function is_verbose_mode(): bool
    {
        return $this->secrets_service->get(Secret::VERBOSE_MODE);
    }
}