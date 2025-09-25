<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollBatch extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'company_id',
        'reference',
        'scheduled_for',
        'status',
        'metadata',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'metadata' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
