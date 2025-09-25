<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'company_id',
        'external_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'salary',
        'currency',
        'metadata',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
