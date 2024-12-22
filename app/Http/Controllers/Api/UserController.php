<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     scheme="basic",
     *     securityScheme="basic_auth"
     * )
     */
    /**
     * @OA\Info(
     *     title="Panel Płatności API",
     *     version="1.0.0",
     *     description="API do zarządzania użytkownikami, płatnościami i fakturami"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="List all users",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="List of users")
     * )
     * 
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created")
     * )
     */    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'users' => 'required|array',
            'users.*.name' => 'required|string',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.password' => 'required|string|min:6'
        ]);

        $users = collect($data['users'])->map(function ($userData) {
            return User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password'])
            ]);
        });

        return response()->json($users, 201);
    }
}
