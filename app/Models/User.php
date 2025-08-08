<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'personnel_id',
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function type(): Attribute
    {
        return new Attribute(
            get: fn($value) =>  ["teacher", "school_head", "admin"][$value],
        );
    }

    public function scopeSearch($query, $value)
    {
        if (!empty($value)) {
            $query->where(function ($q) use ($value) {
                $q->where('email', 'like', "%{$value}%")
                    ->orWhere('role', 'like', "%{$value}%")
                    ->orWhereHas('personnel', function ($personnelQuery) use ($value) {
                        $personnelQuery->where('personnel_id', 'like', "%{$value}%")
                            ->orWhere('first_name', 'like', "%{$value}%")
                            ->orWhere('last_name', 'like', "%{$value}%");
                    });
            });
        }
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function getRole()
    {
        return $this->role;
    }

    public function school()
    {
        return $this->personnel ? $this->personnel->school() : null;
    }

    public function getSchoolId()
    {
        $personnel = $this->personnel;
        if (!$personnel || !$personnel->school) {
            return null;
        }
        return $personnel->school->id;
    }

    public function getSchoolName()
    {
        $personnel = $this->personnel;
        if (!$personnel || !$personnel->school) {
            return null;
        }
        return $personnel->school->school_name;
    }
}
