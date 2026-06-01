<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'numero_seminario', 'data_inicio', 'data_fim', 'local',
        'investimento', 'carga_horaria', 'publico_alvo',
        'programacao', 'folder_palestrantes',
        'topicos', 'arquivo_pdf', 'folder_pdf', 'flyer_downloads', 'ativo', 'ordem',
    ];

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'aluno_curso');
    }

    public function palestrantes()
    {
        return $this->belongsToMany(Palestrante::class, 'curso_palestrante');
    }

    protected $casts = [
        'data_inicio'        => 'datetime',
        'data_fim'           => 'datetime',
        'ativo'              => 'boolean',
        'programacao'        => 'array',
        'folder_palestrantes'=> 'array',
    ];
}
