<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Documento;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $inicio = now()->subMonth()->startOfDay();
        $fim    = now()->addMonth()->endOfDay();

        $cursos = Curso::whereBetween('data_inicio', [$inicio, $fim])
            ->orderBy('data_inicio')
            ->get();

        $documentos = Documento::where('ativo', true)
            ->where(function ($q) {
                $q->whereNull('data_vencimento')->orWhere('data_vencimento', '>=', now()->toDateString());
            })
            ->orderBy('ordem')->orderBy('id')->limit(8)->get();

        return view('admin.dashboard', compact('cursos', 'documentos'));
    }
}
