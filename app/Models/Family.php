<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Family extends Model
{
    use HasFactory;
    protected $table = 'family';
    protected $fillable = ['relationship',
                           'first_name',
                           'middle_name',
                           'last_name',
                           'name_ext',
                           'occupation',
                           'employer_business_name',
                           'business_address',
                           'telephone_number',
                           'date_of_birth'
                        ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function fullName()
    {
        return $this->first_name . ' '
                . ($this->middle_name ? $this->middle_name[0] . '. ' : '')
                . $this->last_name . ' '
                . $this->name_ext;

    }
}
