<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('alunos')->orderBy('data_inicio', 'desc')->get();
        return view('admin.certificados.index', compact('cursos'));
    }

    public function imprimir(Request $request)
    {
        $request->validate([
            'nome'   => 'required|string|max:500',
            'titulo' => 'required|string|max:500',
            'data'   => 'required|string|max:200',
            'cidade' => 'required|string|max:200',
            'topico' => 'nullable|string|max:2000',
        ]);

        return view('admin.certificados.imprimir', [
            'nome'   => strip_tags($request->nome),
            'titulo' => strip_tags($request->titulo),
            'data'   => strip_tags($request->data),
            'cidade' => strip_tags($request->cidade),
            'topico' => strip_tags($request->topico ?? ''),
        ]);
    }
}
