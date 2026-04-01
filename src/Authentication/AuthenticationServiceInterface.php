<?php

namespace App\Authentication;

use App\Authentication\Permissions\Permission;
use App\Authentication\Permissions\PermissionCheckResult;

interface AuthenticationServiceInterface
{
    public function check_permission(Permission $permission): PermissionCheckResult;
    public function should_reauthenticate(): bool;
    public function get_reauthentication_page(): string;
}