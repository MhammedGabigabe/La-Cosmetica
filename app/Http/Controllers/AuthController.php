<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DAO\UserDAO;
use App\DTO\LoginDTO;
use App\DTO\UserDTO;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserDAO $userDAO
    ) {}

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate(UserDTO::rules());
        $dto       = UserDTO::fromRequest($validated);
        $result    = $this->userDAO->createUser($dto);

        return response()->json([
            'message' => 'Inscription réussie.',
            'user'    => $result['user'],
            'token'   => $result['token'],
            'type'    => 'bearer',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate(LoginDTO::rules());
        $dto       = LoginDTO::fromRequest($validated);
        $result    = $this->userDAO->login($dto);

        return response()->json([
            'message' => 'Connexion réussie.',
            'user'    => $result['user'],
            'token'   => $result['token'],
            'type'    => 'bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->userDAO->logout();

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }

    public function refresh(): JsonResponse
    {
        $token = $this->userDAO->refresh();

        return response()->json([
            'token' => $token,
            'type'  => 'bearer',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
       return response()->json(auth('api')->user());
    }
}
