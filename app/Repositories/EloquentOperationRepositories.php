<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EloquentOperationRepositories implements OperationRepositoryInteface {
    public function getAllUsers() {
        return User::all();
    }
    public function getProjectAllUsers(int $projectId) {
        $project = Project::find($projectId);
        return $project->users();
    }
    public function assginRoleToUserPlatform(int $userId, int $roleId) {
        $assignment = DB::table('role_user')->insert([
            'user_id' => $userId,
            'role_id' => $roleId
        ]);

        return $assignment;
    }
    public function assginRoleToUserProject(int $projectId, int $userId, int $roleId) {
        $assignment = DB::table('project_user')->insert([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => $roleId
        ]);

        return $assignment;
    }
    public function removeRoleFromUserPlatform(int $userId) {
        $remove = DB::table('role_user')->insert([
            'user_id' => $userId,
            'role_id' => 4
        ]);

        return $remove;
    }
    public function removeRoleFromUserProject(int $projectId, int $userId) {
        $remove = DB::table('project_user')->insert([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => 4
        ]);

        return $remove;
    }
}

