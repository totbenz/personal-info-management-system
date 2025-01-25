<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;
    protected $table = 'district';
    protected $fillable = [
        'name',
    ];

    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    public function scopeSearch($query, $value){
        $query->where('name', "like", "%{$value}%");
    }

}
