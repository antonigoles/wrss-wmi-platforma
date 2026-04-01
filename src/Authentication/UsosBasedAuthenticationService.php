<?php

namespace App\Authentication;

use App\Authentication\Permissions\Permission;
use App\Authentication\Permissions\PermissionCheckResult;
use App\Environment\EnvironmentInterface;
use App\USOS\OAuthServiceInterface;

readonly class UsosBasedAuthenticationService implements AuthenticationServiceInterface
{

    public function __construct(
        private OAuthServiceInterface $oauth_service,
        private EnvironmentInterface $environment,
    ) {}

    public function check_permission(Permission $permission): PermissionCheckResult
    {
        $user_identifier = "guest";
        if ($this->should_reauthenticate()) {
            return match ($permission) {
                Permission::DISPLAY_HOME_PAGE => new PermissionCheckResult($permission, $user_identifier, true),
                default => new PermissionCheckResult($permission, $user_identifier, false),
            };
        }
        $user_identifier = $this->oauth_service->fetch_user_id();
        return new PermissionCheckResult($permission, $user_identifier, true);
    }

    public function should_reauthenticate(): bool
    {
        return $this->oauth_service->should_reauthenticate();
    }

    public function get_reauthentication_page(): string
    {
        return $this->environment->get_app_url() . '/usos_oauth.php';
    }
}