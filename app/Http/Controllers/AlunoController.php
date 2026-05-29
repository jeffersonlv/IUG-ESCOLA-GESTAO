<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Curso;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    private static $estados = [
        'AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT',
        'PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO',
    ];

    public function adminIndex(Request $request)
    {
        $q = $request->input('q');
        $qs = $q ? '%' . str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $q) . '%' : null;
        $alunos = Aluno::when($qs, fn($query) => $query->where('nome_completo', 'like', $qs)
                ->orWhere('cidade', 'like', $qs)
                ->orWhere('estado', 'like', $qs))
            ->orderBy('nome_completo')
            ->paginate(20)
            ->withQueryString();
        return view('admin.alunos.index', compact('alunos', 'q'));
    }

    public function adminCreate()
    {
        $cursos = Curso::orderBy('data_inicio', 'desc')->get();
        return view('admin.alunos.create', ['cursos' => $cursos, 'estados' => self::$estados]);
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cidade'        => 'required|string|max:100',
            'estado'        => 'required|string|size:2',
            'cursos'        => 'nullable|array',
            'cursos.*'      => 'exists:cursos,id',
        ]);

        $aluno = Aluno::create([
            'nome_completo' => $validated['nome_completo'],
            'cidade'        => $validated['cidade'],
            'estado'        => strtoupper($validated['estado']),
        ]);

        if (!empty($validated['cursos'])) {
            $aluno->cursos()->sync($validated['cursos']);
        }

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno cadastrado com sucesso.');
    }

    public function adminEdit($id)
    {
        $aluno = Aluno::with('cursos')->findOrFail($id);
        $cursos = Curso::orderBy('data_inicio', 'desc')->get();
        $cursosSelecionados = $aluno->cursos->pluck('id')->toArray();
        return view('admin.alunos.edit', compact('aluno', 'cursos', 'cursosSelecionados') + ['estados' => self::$estados]);
    }

    public function adminUpdate(Request $request, $id)
    {
        $aluno = Aluno::findOrFail($id);
        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'cidade'        => 'required|string|max:100',
            'estado'        => 'required|string|size:2',
            'cursos'        => 'nullable|array',
            'cursos.*'      => 'exists:cursos,id',
        ]);

        $aluno->update([
            'nome_completo' => $validated['nome_completo'],
            'cidade'        => $validated['cidade'],
            'estado'        => strtoupper($validated['estado']),
        ]);

        $aluno->cursos()->sync($validated['cursos'] ?? []);

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->cursos()->detach();
        $aluno->delete();
        return redirect()->route('admin.alunos.index')->with('success', 'Aluno removido.');
    }
}
