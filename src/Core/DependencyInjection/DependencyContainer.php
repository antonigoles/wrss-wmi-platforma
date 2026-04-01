<?php

namespace App\Core\DependencyInjection;

use App\Core\Environment\Environment;
use App\Core\Environment\EnvironmentInterface;
use App\Core\Secrets\FileBasedSecretsService;
use App\Core\Secrets\SecretsServiceInterface;
use App\Core\Session\SessionInterface;
use App\Core\Session\StandardSession;
use App\Database\DatabaseConnection;
use App\Database\DatabaseConnectionInterface;
use App\Database\DatabaseConnectionOptions;
use App\USOS\OAuthService as UsosOAuth;
use App\USOS\OAuthServiceInterface as UsosOAuthServiceInterface;

class DependencyContainer
{
    private static DependencyContainer|null $instance = null;
    private array $map = [];

    private function build(): void
    {
        $secrets_service = new FileBasedSecretsService();
        $session = new StandardSession();
        $environment = new Environment(
            secrets_service: $secrets_service
        );

        $this->map = [
            SessionInterface::class => $session,
            DatabaseConnectionInterface::class => new DatabaseConnection(
                connection_options: new DatabaseConnectionOptions()
            ),
            SecretsServiceInterface::class => $secrets_service,
            EnvironmentInterface::class => $environment,

            // USOS
            UsosOAuthServiceInterface::class => new UsosOAuth(
                session: $session,
                environment: $environment,
                secrets_service: $secrets_service
            ),
        ];
    }

    /**
     * Hard-wiring for now - maybe lets implement PHP-DI in the future
     *
     * @template T
     * @param string $class_name
     * @return T
     */
    public static function get(string $class_name): mixed {
        if (self::$instance === null) {
            self::$instance = new DependencyContainer();
            self::$instance->build();
        }

        if (!(self::$instance->map[$class_name] ?? false)) {
            if (self::$instance->map[EnvironmentInterface::class]->is_developer_mode()) {
                var_dump(self::$instance->map);
            }

            throw new \RuntimeException("Missing definition");
        };

        return self::$instance->map[$class_name];
    }
}

?>