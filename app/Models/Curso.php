<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'data_inicio', 'data_fim', 'local', 'folder_pdf', 'ativo', 'ordem'];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'ativo' => 'boolean',
    ];
}
