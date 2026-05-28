<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'data_inicio', 'data_fim', 'local', 'topicos', 'arquivo_pdf', 'folder_pdf', 'ativo', 'ordem'];

    public function palestrantes()
    {
        return $this->belongsToMany(Palestrante::class, 'curso_palestrante');
    }

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'ativo' => 'boolean',
    ];
}
