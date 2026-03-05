<?php

namespace App\Services;

use App\Repositories\OperationRepositoryInteface;

class OperationServices {
    protected $operations;
    public function __construct(OperationRepositoryInteface $operationRepositoryInteface)
    {
        $this->operations = $operationRepositoryInteface;
    }
    public function getUsersPlatformService() {
        return $this->operations->getAllUsers();
    }

    public function getProjectAllUsers(int $projectId) {
        return $this->operations->getProjectAllUsers($projectId);
    }

    public function assginRolePlatformService(array $data) {
        return $this->operations->assginRoleToUserPlatform($data['user_id'], $data['role_id']);
    }

    public function assginRoleProjectService(array $data) {
        return $this->operations->assginRoleToUserProject($data['project_id'], $data['user_id'], $data['role_id']);
    }

    public function removeRolePlatformService(array $data) {
        return $this->operations->removeRoleFromUserPlatform($data['user_id']);
    }

    public function removeRoleProjectService(array $data) {
        return $this->operations->removeRoleFromUserProject($data['project_id'], $data['user_id']);
    }

}
