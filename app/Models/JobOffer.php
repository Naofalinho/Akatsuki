<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'category',
        'location',
        'contract',
        'salary',
        'description',
        'requirements',
        'benefits',
        'deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    /**
     * Recruteur ayant publié l'offre.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}