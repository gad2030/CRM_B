<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
