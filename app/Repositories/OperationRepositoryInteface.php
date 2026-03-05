<?php

namespace App\Repositories;

interface OperationRepositoryInteface {
    public function getAllUsers();
    public function getProjectAllUsers(int $projectId);
    public function assginRoleToUserPlatform(int $userId, int $roleId);
    public function assginRoleToUserProject(int $projectId, int $user_id, int $role_id);
    public function removeRoleFromUserPlatform(int $userId);
    public function removeRoleFromUserProject(int $projectId, int $userId);
}
