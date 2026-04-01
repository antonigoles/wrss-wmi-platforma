<?php

namespace App\Website\Router;

use App\Authentication\AuthenticationServiceInterface;
use App\Authentication\Permissions\Permission;
use App\Environment\EnvironmentInterface;
use App\Session\SessionInterface;
use App\Utilities\ArrayUtilities;

class RouterService
{
    public function __construct(
        private AuthenticationServiceInterface $authentication_service,
        private EnvironmentInterface $environment,
        private SessionInterface $session
    ) {}

    private string $unauthorized_page = 'logout.php';

    public function set_unauthenticated_redirect_page(string $path): void
    {
        $this->unauthorized_page = $path;
    }

    public function require_permissions(array $permissions): void
    {
        $authentication_service = $this->authentication_service;
        $permission_check = ArrayUtilities::all(
            $permissions,
            function (Permission $permission) use ($authentication_service) {
                return $authentication_service->check_permission($permission)->has_passed();
            }
        );

        if (!$permission_check) {
            $this->session->write("return_url", $this->environment->get_unparsed_current_url());
            $url = $this->environment->get_app_url() . $this->unauthorized_page;
            header('Location: '.$url);
            die();
        };
    }
}