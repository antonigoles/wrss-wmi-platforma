<?php

namespace App\Authentication;

use App\Authentication\Permissions\Permission;
use App\Authentication\Permissions\PermissionCheckResult;
use App\User\User;

interface AuthenticationServiceInterface
{
    public function check_permission(Permission $permission): PermissionCheckResult;
    public function should_reauthenticate(): bool;
    public function is_logged_in(): bool;
    public function get_reauthentication_page(): string;
    public function get_current_session_user(): ?User;
}