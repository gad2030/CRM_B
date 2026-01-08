<?php

namespace App\Models;

use App\Models\Concerns\BelongsToEmployer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory, BelongsToEmployer;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'industry',
        'website',
        'phone',
        'address',
        'billing_address',
        'owner_id',
        'employer_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the owner (user) that owns the account.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the employer that owns the account.
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    /**
     * Get the contacts associated with the account.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'account_id');
    }

    /**
     * Get the opportunities associated with the account.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'account_id');
    }

    /**
     * Get the interactions associated with the account.
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class, 'account_id');
    }
}

