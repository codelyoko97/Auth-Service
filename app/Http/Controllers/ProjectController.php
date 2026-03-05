<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\VerifyProjectJoinRequest;
use App\Models\Invitation;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Services\JwtService;
use App\Services\ProjectServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    protected $jwtService;
    protected $projectService;

    public function __construct(JwtService $jwtService, ProjectServices $projectServices)
    {
        $this->jwtService = $jwtService;
        $this->projectService = $projectServices;
    }

    public function createProject(ProjectRequest $projectRequest) {
        $user = User::find($projectRequest->owner_id);

        if(!$user) {
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }

        if(!$user->is_verified) {
            return response()->json([
                'message' => 'Account Not Verified, Verify It First'
            ], 400);
        }

        $data = $projectRequest->only(['owner_id', 'name','slug','is_active','settings']);

        $project = $this->projectService->createProjectService($data);

        $this->jwtService->generateProjectToken($user->id, $project->id, 'owner');

        return $project;
    }

    public function invitePerson(InvitationRequest $invitationRequest) {
        $data = $invitationRequest->only('project_id','role_id','email');
        $invitation = $this->projectService->creteInvitationService($data);
        if($invitation) {
            return response()->json([
                'message' => 'Invitaion sent successfully',
                'data' => $invitation
            ]);
        }
        return response()->json('Somthing went wrong please try again!');
    }

    public function verifyJoinProject(VerifyProjectJoinRequest $verify) {
        $user = DB::table('users')
            ->where('email', $verify->email)
            ->first();
        if(!$user) {
            return response()->json([
                'error' => 'Access Denied!, You have to register in our Platform',
            ],403);
        }

        $invitation = DB::table('project_invitations')
            ->where('project_id', $verify->project_id)
            ->where('email', $verify->email)
            ->first();

        if(!$invitation) {
            return response()->json([
                'error' => 'Access Denied!',
            ],403);
        }

        $invit = Invitation::find($invitation->id);
        $data = $verify->only(['project_id','email', 'otp']);
        $data['user_id'] = $user->id;
        $join = $this->projectService->verifyOTP($invit, $data);

        if(!$join) {
            return response()->json([
                'message' => 'You cannot join this project, or you are already a member!'
            ],403);
        }

        DB::table('project_user')->insert([
            'project_id' => $verify->project_id,
            'user_id' => $user->id,
            'role_id' => $invitation->role_id
        ]);
        return response()->json([
            'message' => 'Joined Successfully'
        ],200);
    }

    public function select(Request $request) {
        $userId = $request->auth_user_id;
        $projectId = $request->project_id;

        $membership = DB::table('project_user')
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        $project = Project::findOrFail($projectId);

        if(!$membership && $project->is_public) {
            $memberRole = Role::where('name', 'member')->first();

            DB::table('project_user')->insert([
                'project_id' => $projectId,
                'user_id' => $userId,
                'role_id' => $memberRole->id,
            ]);

            $roleName = 'member';
        }else if($membership) {
            $role = Role::find($membership->role_id);
            $roleName = $role->name;
        } else {
            return response()->json([
                'error' => 'Access Denied!',
            ],403);
        }

        $projectToken = $this->jwtService->generateProjectToken($userId, $projectId, $roleName);

        return response()->json([
            'project_token' => $projectToken
        ]);
    }
}
