<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = ['nome_completo', 'cidade', 'estado'];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'aluno_curso');
    }
}
