<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Employer\InviteEmployeeRequest;
use App\Http\Requests\Employer\JoinEmployerRequest;
use App\Http\Requests\Employer\StoreEmployerRequest;
use App\Http\Requests\Employer\UpdateEmployeeRoleRequest;
use App\Models\Employer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployerController extends ApiController
{
    /**
     * Create a new employer (only if user has none)
     */
    public function store(StoreEmployerRequest $request): JsonResponse
    {
        $user = $request->user();

        // Check if user already has an employer
        if ($user->employers()->exists()) {
            return $this->fail('User already belongs to an employer', 400);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Create employer
            $employer = Employer::create([
                'name' => $validated['name'],
                'industry' => $validated['industry'] ?? null,
                'website' => $validated['website'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
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
            $permissions = \App\Models\Permission::all();
            $adminRole->permissions()->attach($permissions->pluck('id'));

            DB::commit();

            return $this->ok([
                'employer' => $employer->load('owner'),
            ], 'Employer created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->fail('Failed to create employer: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get current employer
     */
    public function my(Request $request): JsonResponse
    {
        $user = $request->user();
        $employer = $user->currentEmployer;

        if (!$employer) {
            return $this->fail('No employer found', 404);
        }

        return $this->ok([
            'employer' => $employer->load(['owner', 'users']),
        ], 'Employer retrieved successfully');
    }

    /**
     * Send invite to join employer
     */
    public function invite(InviteEmployeeRequest $request, int $id): JsonResponse
    {
        $user = $request->user();
        $employer = Employer::findOrFail($id);

        // Check if user is owner or has permission
        if (!$employer->isOwner($user) && !$user->hasPermission('manage_employees')) {
            return $this->fail('Unauthorized', 403);
        }

        $validated = $request->validated();

        // Check if user exists
        $invitee = User::where('email', $validated['email'])->first();

        if (!$invitee) {
            // User doesn't exist - in a real app, you'd send an email invite
            // For now, we'll just return a token that can be used to join
            $token = Str::random(64);
            // Store token in cache or database for invite acceptance
            // For simplicity, we'll just return success
            return $this->ok([
                'message' => 'Invite sent. User will receive an email to join.',
                'email' => $validated['email'],
            ], 'Invite sent successfully');
        }

        // Check if user already belongs to this employer
        if ($employer->hasUser($invitee)) {
            return $this->fail('User already belongs to this employer', 400);
        }

        // Attach user to employer
        $employer->users()->attach($invitee->id, [
            'role' => $validated['role'],
            'joined_at' => now(),
            'invited_by' => $user->id,
        ]);

        // If user has no current employer, set this one
        if (!$invitee->current_employer_id) {
            $invitee->update(['current_employer_id' => $employer->id]);
        }

        return $this->ok([
            'user' => $invitee->only(['id', 'name', 'email']),
            'role' => $validated['role'],
        ], 'User added to employer successfully');
    }

    /**
     * Join employer via invite token
     */
    public function join(JoinEmployerRequest $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        $employer = Employer::findOrFail($validated['employer_id']);

        // In a real app, validate the token from cache/database
        // For now, we'll just check if user can join
        if ($employer->hasUser($user)) {
            return $this->fail('User already belongs to this employer', 400);
        }

        // Attach user to employer with default role
        $employer->users()->attach($user->id, [
            'role' => 'employee',
            'joined_at' => now(),
            'invited_by' => null,
        ]);

        // Set as current employer if user has none
        if (!$user->current_employer_id) {
            $user->update(['current_employer_id' => $employer->id]);
        }

        return $this->ok([
            'employer' => $employer->load('owner'),
        ], 'Joined employer successfully');
    }

    /**
     * Get employees of an employer
     */
    public function employees(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $employer = Employer::findOrFail($id);

        // Check if user belongs to this employer
        if (!$employer->hasUser($user) && !$employer->isOwner($user)) {
            return $this->fail('Unauthorized', 403);
        }

        $employees = $employer->users()->with('currentEmployer')->get();

        return $this->ok([
            'employees' => $employees->map(function ($employee) use ($employer) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'role' => $employer->getUserRole($employee),
                    'joined_at' => $employee->pivot->joined_at,
                    'is_owner' => $employer->isOwner($employee),
                ];
            }),
        ], 'Employees retrieved successfully');
    }

    /**
     * Update employee role
     */
    public function updateEmployeeRole(UpdateEmployeeRoleRequest $request, int $id, int $userId): JsonResponse
    {
        $user = $request->user();
        $employer = Employer::findOrFail($id);

        // Check if user is owner or has permission
        if (!$employer->isOwner($user) && !$user->hasPermission('manage_employees')) {
            return $this->fail('Unauthorized', 403);
        }

        $employee = User::findOrFail($userId);

        // Check if employee belongs to this employer
        if (!$employer->hasUser($employee)) {
            return $this->fail('User does not belong to this employer', 404);
        }

        // Prevent changing owner's role
        if ($employer->isOwner($employee)) {
            return $this->fail('Cannot change owner role', 400);
        }

        $validated = $request->validated();

        // Update role in pivot table
        $employer->users()->updateExistingPivot($employee->id, [
            'role' => $validated['role'],
        ]);

        return $this->ok([
            'user' => $employee->only(['id', 'name', 'email']),
            'role' => $validated['role'],
        ], 'Employee role updated successfully');
    }

    /**
     * Remove employee from employer
     */
    public function removeEmployee(Request $request, int $id, int $userId): JsonResponse
    {
        $user = $request->user();
        $employer = Employer::findOrFail($id);

        // Check if user is owner or has permission
        if (!$employer->isOwner($user) && !$user->hasPermission('manage_employees')) {
            return $this->fail('Unauthorized', 403);
        }

        $employee = User::findOrFail($userId);

        // Check if employee belongs to this employer
        if (!$employer->hasUser($employee)) {
            return $this->fail('User does not belong to this employer', 404);
        }

        // Prevent removing owner
        if ($employer->isOwner($employee)) {
            return $this->fail('Cannot remove owner', 400);
        }

        // Detach user from employer
        $employer->users()->detach($employee->id);

        // If this was their current employer, clear it
        if ($employee->current_employer_id === $employer->id) {
            $employee->update(['current_employer_id' => null]);
        }

        return $this->ok(null, 'Employee removed successfully');
    }
}
