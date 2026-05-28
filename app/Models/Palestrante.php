<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palestrante extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'foto', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_palestrante');
    }
}
