<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'current_employer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the accounts owned by the user.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'owner_id');
    }

    /**
     * Get the contacts owned by the user.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'owner_id');
    }

    /**
     * Get the leads owned by the user.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'owner_id');
    }

    /**
     * Get the opportunities owned by the user.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'owner_id');
    }

    /**
     * Get the interactions created by the user.
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class, 'user_id');
    }

    /**
     * Get the employers that the user belongs to.
     */
    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Employer::class, 'employer_user')
            ->withPivot('role', 'joined_at', 'invited_by')
            ->withTimestamps();
    }

    /**
     * Get the current employer.
     */
    public function currentEmployer(): BelongsTo
    {
        return $this->belongsTo(Employer::class, 'current_employer_id');
    }

    /**
     * Get the employers owned by the user.
     */
    public function ownedEmployers(): HasMany
    {
        return $this->hasMany(Employer::class, 'owner_id');
    }

    /**
     * Check if user is owner of an employer.
     */
    public function isOwnerOf(Employer $employer): bool
    {
        return $employer->owner_id === $this->id;
    }

    /**
     * Check if user belongs to an employer.
     */
    public function belongsToEmployer(Employer $employer): bool
    {
        return $this->employers()->where('employers.id', $employer->id)->exists();
    }

    /**
     * Get the role of the user in a specific employer.
     */
    public function getRoleInEmployer(Employer $employer): ?string
    {
        $pivot = $this->employers()->where('employers.id', $employer->id)->first()?->pivot;
        return $pivot?->role;
    }

    /**
     * Check if user has a permission in the current employer.
     */
    public function hasPermission(string $permissionName): bool
    {
        // Owner has all permissions
        if ($this->currentEmployer && $this->isOwnerOf($this->currentEmployer)) {
            return true;
        }

        if (!$this->currentEmployer) {
            return false;
        }

        $userRole = $this->getRoleInEmployer($this->currentEmployer);
        if (!$userRole) {
            return false;
        }

        // Find role and check permissions
        $role = $this->currentEmployer->roles()
            ->where('name', $userRole)
            ->first();

        return $role && $role->hasPermission($permissionName);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is sales.
     */
    public function isSales(): bool
    {
        return $this->role === 'sales';
    }
}
