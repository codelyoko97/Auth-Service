<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MeController extends Controller
{
    public function index(Request $request)
    {

        $token = $request->bearerToken();

        $publicKey = file_get_contents(storage_path('keys/jwt_public.pem'));

        $decoded = JWT::decode($token,new Key($publicKey,'RS256'));

        // $user = User::find($decoded->sub);
        $user = User::find($decoded->sub);

        return response()->json($user);
    }
}
