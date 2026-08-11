<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'gender',
        'position',
        'user',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Many-to-Many Relationship with Position
     */
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_user', 'user_id', 'position_id');
    }

    /**
     * Legacy Single Position Relationship
     */
    public function pos()
    {
        return $this->belongsTo(Position::class, 'position');
    }

    /**
     * Check if user holds a specific position by name or if user is Administrator
     */
    public function hasPosition(string $positionName): bool
    {
        // Admin has access to all roles
        if ($this->position == 1 || $this->positions()->where('name', 'Administrator')->exists()) {
            return true;
        }

        // Check legacy position field or pivot table
        if ($this->pos && strtolower($this->pos->name) === strtolower($positionName)) {
            return true;
        }

        return $this->positions()->where('name', 'like', "%{$positionName}%")->exists();
    }

    /**
     * Check if user holds any of the specified positions
     */
    public function hasAnyPosition(array $positionNames): bool
    {
        foreach ($positionNames as $name) {
            if ($this->hasPosition($name)) {
                return true;
            }
        }
        return false;
    }
}
