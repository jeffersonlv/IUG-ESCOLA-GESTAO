<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'arquivo_pdf', 'ativo', 'ordem', 'data_vencimento'];

    protected $casts = [
        'ativo'            => 'boolean',
        'data_vencimento'  => 'date',
    ];

    public function getVencidoAttribute(): bool
    {
        return $this->data_vencimento && $this->data_vencimento->isPast();
    }
}
