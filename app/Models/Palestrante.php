<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palestrante extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'foto', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;
        if (strpos($this->foto, '/') === 0) return $this->foto;
        return \Illuminate\Support\Facades\Storage::url('palestrantes/' . $this->foto);
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_palestrante');
    }
}
