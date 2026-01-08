<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Employer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Automatically create an Employer for the new user
            $employer = Employer::create([
                'name' => $request->name . "'s Company", // Default name, can be changed later
                'owner_id' => $user->id,
            ]);

            // Attach user as owner with 'admin' role
            $employer->users()->attach($user->id, [
                'role' => 'admin',
                'joined_at' => now(),
                'invited_by' => null,
            ]);

            // Set as current employer
            $user->update(['current_employer_id' => $employer->id]);

            // Create default admin role for this employer
            $adminRole = Role::create([
                'employer_id' => $employer->id,
                'name' => 'admin',
                'is_system' => true,
            ]);

            // Attach all permissions to admin role
            $permissions = Permission::all();
            if ($permissions->isNotEmpty()) {
                $adminRole->permissions()->attach($permissions->pluck('id'));
            }

            DB::commit();

            $token = $user->createToken('auth-token')->plainTextToken;

            return $this->ok([
                'user' => new UserResource($user->load('currentEmployer')),
                'token' => $token,
            ], 'User registered successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->fail('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Login user and create token
     */
    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->ok([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok(null, 'Logged out successfully');
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return $this->ok(
            new UserResource($request->user()),
            'User retrieved successfully'
        );
    }
}

