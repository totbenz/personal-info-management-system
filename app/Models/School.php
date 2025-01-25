<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    use HasFactory;
    protected $fillable = ['school_id',
                           'school_name',
                           'address',
                           'division',
                           'district_id',
                           'email',
                           'phone',
                           'curricular_classification'];
    protected $casts = ['curricular_classification' => 'json'];
    protected $hidden = ['created_at',
                         'updated_at'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function personnels(): HasMany
    {
        return $this->hasMany(Personnel::class);
    }

    public function schoolHead(): HasOne
    {
        return $this->hasOne(Personnel::class)
                    ->whereHas('position', function ($query) {
                        $query->whereIn('title', [
                            'School Principal I',
                            'School Principal II',
                            'School Principal III',
                            'School Principal IV'
                        ]);
                    });
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Personnel::class)
                    ->whereHas('position', function ($query) {
                        $query->whereIn('title', [
                            'Head Teacher I',
                            'Head Teacher II',
                            'Head Teacher III',
                            'Head Teacher IV',
                            'Master Teacher I',
                            'Master Teacher II',
                            'Master Teacher III',
                            'Special Education Teacher I',
                            'Special Education Teacher II',
                            'Special Education Teacher III'
                        ]);
                    });
    }

    public function getSchoolYear()
    {
        $currentMonth = date('n'); // 'n' gives month without leading zero (1-12)
        $currentYear = date('Y');  // 'Y' gives the full numeric year (e.g., 2023)

        // Determine the year range based on the current month
        if ($currentMonth < 6) {
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        } else {
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        }

        // Display the year range
        return $startYear . '-' . $endYear;
    }

    public function scopeSearch($query, $value){
        $query->where('school_id', "like", "%{$value}%")
                ->orWhere('school_name', 'like', "%{$value}%")
                ->orWhere('address', 'like', "%{$value}%");
    }
}
