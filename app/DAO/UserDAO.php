<?php

namespace App\DAO;

use App\DTO\UserDTO;
use App\DTO\LoginDTO;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UserDAO
{
    public function createUser(UserDTO $dto): array
    {
        $isFirst = User::count() === 0;

        $user=User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => Hash::make($dto->password),
            'role'     => $isFirst ? 'admin' : $dto->role,
        ]);

        $token = auth('api')->login($user);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function login(LoginDTO $dto): array
    {

        $token = auth('api')->attempt([
            'email'    => $dto->email,
            'password' => $dto->password,
        ]);

        if (!$token) {
            throw new \Exception('Email ou mot de passe incorrect.', 401);
        }

        return [
            'user'  => auth('api')->user(),
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        
        auth('api')->logout();
    }

    public function refresh(): string
    {
        return auth('api')->refresh();
    }

    // public function findByEmail(string $email): ?User
    // {
    //     return User::where('email', $email)->first();
    // }
}