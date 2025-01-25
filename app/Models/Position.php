<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;
    protected $table = 'position';
    protected $fillable = ['title', 'classification'];

    public function personnels(): HasMany
    {
        return $this->hasMany(Personnel::class);
    }

    public function scopeSearch($query, $value){
        $query->where('id', "like", "%{$value}%")
               ->orWhere('title', "like", "%{$value}%");
    }

    public function abbreviateTitle()
    {
        $abbreviations = [
            'Teacher I' => 'T-I',
            'Teacher II' => 'T-II',
            'Teacher III' => 'T-III',
            'Master Teacher I' => 'MT-I',
            'Master Teacher II' => 'MT-II',
            'Master Teacher III' => 'MT-III',
            'Master Teacher IV' => 'MT-IV',
            'Head Teacher I' => 'HT-I',
            'Head Teacher II' => 'HT-II',
            'Head Teacher III' => 'HT-III',
            'Head Teacher IV' => 'HT-IV',
            'Head Teacher V' => 'HT-V',
            'Head Teacher VI' => 'HT-VI',
            'Special Education Teacher I' => 'SET-I',
            'Special Education Teacher II' => 'SET-II',
            'Special Education Teacher III' => 'SET-III',
            'Special Education Teacher IV' => 'SET-IV',
            'Special Education Teacher V' => 'SET-V',
            'School Principal I' => 'P-I',
            'School Principal II' => 'P-II',
            'School Principal III' => 'P-III',
            'School Principal IV' => 'P-IV',
        ];

        return $abbreviations[$this->title] ?? $this->title;
    }
}
