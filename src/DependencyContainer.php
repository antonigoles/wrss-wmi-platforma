<?php

namespace App;

use App\Authentication\AuthenticationServiceInterface;
use App\Authentication\UsosBasedAuthenticationService;
use App\Database\DatabaseConnection;
use App\Database\DatabaseConnectionInterface;
use App\Database\DatabaseConnectionOptions;
use App\Environment\Environment;
use App\Environment\EnvironmentInterface;
use App\KonkursProwadzacych\KPRepository;
use App\KonkursProwadzacych\KPService;
use App\Secrets\FileBasedSecretsService;
use App\Secrets\SecretsServiceInterface;
use App\Session\SessionInterface;
use App\Session\StandardSession;
use App\User\UserRepository;
use App\User\UserRepositoryInterface;
use App\USOS\OAuthService as UsosOAuth;
use App\USOS\OAuthServiceInterface as UsosOAuthServiceInterface;
use App\Website\Router\RouterService;

class DependencyContainer
{
    private static DependencyContainer|null $instance = null;
    private array $map = [];

    private function build(): void
    {
        $database_connection = new DatabaseConnection(
            connection_options: new DatabaseConnectionOptions()
        );
        $secrets_service = new FileBasedSecretsService();
        $session = new StandardSession();
        $environment = new Environment(
            secrets_service: $secrets_service
        );
        $usos_oauth_service =  new UsosOAuth(
            session: $session,
            environment: $environment,
            secrets_service: $secrets_service
        );
        $user_repository = new UserRepository($database_connection);
        $authentication_service =  new UsosBasedAuthenticationService(
            oauth_service: $usos_oauth_service,
            environment: $environment,
            user_repository: $user_repository
        );
        $kp_repository = new KPRepository($database_connection);

        $this->map = [
            SessionInterface::class => $session,
            DatabaseConnectionInterface::class => $database_connection,
            SecretsServiceInterface::class => $secrets_service,
            EnvironmentInterface::class => $environment,
            UsosOAuthServiceInterface::class => $usos_oauth_service,
            AuthenticationServiceInterface::class => $authentication_service,
            RouterService::class => new RouterService(
                $authentication_service,
                $environment,
                $session
            ),
            UserRepositoryInterface::class => $user_repository,
            KPService::class => new KPService(
                kp_repository: $kp_repository
            )
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