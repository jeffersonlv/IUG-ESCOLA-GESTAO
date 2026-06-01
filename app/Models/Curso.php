<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($curso) {
            if (empty($curso->slug)) {
                $curso->slug = static::gerarSlug($curso->titulo, $curso->id ?? 0);
            }
        });

        static::created(function ($curso) {
            if (str_ends_with($curso->slug, '_0')) {
                $curso->slug = static::gerarSlug($curso->titulo, $curso->id);
                $curso->saveQuietly();
            }
        });
    }

    public static function gerarSlug(string $titulo, int $id): string
    {
        $base = \Str::slug($titulo . '-' . $id, '-');
        $str  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $base);
        $str  = preg_replace('/[^a-zA-Z0-9\s_-]/', '', $str);
        $str  = trim(preg_replace('/[\s_-]+/', '_', $str), '_');
        return strtolower($str);
    }

    protected $fillable = [
        'titulo', 'slug', 'numero_seminario', 'data_inicio', 'data_fim', 'local',
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
