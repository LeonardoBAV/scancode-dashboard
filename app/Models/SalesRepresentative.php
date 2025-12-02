<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRepresentative extends Model
{
    /** @use HasFactory<\Database\Factories\SalesRepresentativeFactory> */
    use HasFactory;

    protected $fillable = [
        'cpf',
        'name',
        'email',
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
