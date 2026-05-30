<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', 'nome', 'fundo', 'largura_mm', 'altura_mm', 'orientacao', 'layout', 'ativo'
    ];

    protected $casts = [
        'layout' => 'array',
        'ativo' => 'boolean',
        'largura_mm' => 'integer',
        'altura_mm' => 'integer',
    ];
}
