<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /* create customer user and token */
    public function register(array $data): array
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'user',
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return ['user' => $user, 'token' => $token];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* verify credentials and issue token */
    public function login(array $data): ?array
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $data['email'])->first();
            if (!$user || !Hash::check($data['password'], $user->password)) {
                DB::rollBack();
                return null;
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return ['user' => $user, 'token' => $token];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* revoke active access token */
    public function logout(User $user): void
    {
        DB::beginTransaction();
        try {
            $user->currentAccessToken()->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}