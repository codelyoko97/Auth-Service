<?php

namespace App\Http\Controllers;

use App\Services\OperationServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    protected $operations;

    public function __construct(OperationServices $operationServices)
    {
        $this->operations = $operationServices;
    }

    public function getAllUsers() {
        $user = Auth::user();
        if($user) {
            $role = DB::table('role_user')
                ->where('role_id', 2)
                ->where('user_id', $user->id)
                ->first();

            if(!$role) {
                return response()->json([
                    'message' => 'Not authorized',
                ], 401);
            }

            $users = $this->operations->getUsersPlatformService();

            return response()->json([
                'message' => 'Plataform Users:',
                'data' => $users,
            ], 200);
        }

        return response()->json([
                'message' => 'Somthig went wrong! User not found',
            ], 404);
    }

    public function getAllProjectUsers($projectId) {
        $user = Auth::user();
        if($user) {
            $project = DB::table('project_user')
                ->where('project_id', $projectId)
                ->where('user_id', $user->id)
                ->where('role_id', 1)
                ->first();

            if(!$project) {
                return response()->json([
                    'message' => 'Not authorized',
                ], 401);
            }

            $users = $this->operations->getProjectAllUsers($project->id);

            return response()->json([
                'message' => 'Project Users:',
                'data' => $users,
            ], 200);
        }

        return response()->json([
                'message' => 'Somthig went wrong! User not found',
            ], 404);
    }

    public function assginRoleToUserPlatform(Request $request) {
        $user = $request->user();
        $data = $request->only(['user_id', 'role_id']);

        if($user) {
            $role = DB::table('role_user')
                ->where('role_id', 2)
                ->where('user_id', $user->id)
                ->first();

            if(!$role) {
                return response()->json([
                    'message' => 'Not authorized',
                ], 401);
            }

            $assignment = $this->operations->assginRolePlatformService($data);

            if($assignment) {
                return response()->json([
                    'message' => 'Done',
                ], 200);
            }
        }

        return response()->json([
                'message' => 'Somthig went wrong!',
            ], 404);
    }

    public function assginRoleToUserProject() {}

    public function removeRoleFromUserPlatform() {}

    public function removeRoleFromUserProject() {}
}
