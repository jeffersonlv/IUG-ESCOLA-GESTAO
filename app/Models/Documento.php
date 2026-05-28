<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'arquivo_pdf', 'ativo', 'ordem'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
