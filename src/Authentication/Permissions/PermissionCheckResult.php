<?php

namespace App\Authentication\Permissions;

readonly class PermissionCheckResult
{
    public function __construct(
        private Permission $permission,
        private string $user_identifier,
        private bool $has_passed
    ) {}

    public function get_permission(): Permission
    {
        return $this->permission;
    }

    public function get_user_identifier(): string
    {
        return $this->user_identifier;
    }

    public function has_passed(): bool
    {
        return $this->has_passed;
    }
}