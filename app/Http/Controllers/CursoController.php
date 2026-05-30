<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Palestrante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::where('ativo', true)->orderBy('ordem')->orderBy('data_inicio')->get();
        return view('cursos.index', compact('cursos'));
    }

    public function show($id)
    {
        $curso = Curso::where('ativo', true)->findOrFail($id);
        return view('cursos.show', compact('curso'));
    }

    public function adminIndex(Request $request)
    {
        $q = $request->input('q');
        $qs = $q ? '%' . str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $q) . '%' : null;

        $sortable = ['titulo', 'data_inicio', 'local'];
        $sort = in_array($request->input('sort'), $sortable) ? $request->input('sort') : null;
        $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';

        $baseQuery = Curso::when($qs, fn($q) => $q->where('titulo', 'like', $qs)->orWhere('local', 'like', $qs));

        if ($sort) {
            $baseQuery->orderBy($sort, $dir);
        } else {
            $baseQuery->orderBy('ordem')->orderBy('data_inicio');
        }

        if ($qs) {
            $cursos   = $baseQuery->get();
            $proximos = collect();
            $passados = collect();
        } else {
            $cursos   = collect();
            $proximos = (clone $baseQuery)->whereDate('data_fim', '>=', now()->toDateString())->get();
            $passados = (clone $baseQuery)->whereDate('data_fim', '<', now()->toDateString())
                ->when(!$sort, fn($q) => $q->reorder()->orderByDesc('data_fim'))
                ->paginate(10)->withQueryString();
        }

        return view('admin.cursos.index', compact('cursos', 'proximos', 'passados', 'q', 'sort', 'dir'));
    }

    public function adminCreate()
    {
        $palestrantes = Palestrante::where('ativo', true)->orderBy('nome')->get();
        return view('admin.cursos.create', compact('palestrantes'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
            'local'       => 'required|string|max:255',
            'topicos'     => 'nullable|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'ativo'       => 'boolean',
            'ordem'       => 'nullable|integer|min:0|max:999',
        ]);

        if ($request->hasFile('arquivo_pdf')) {
            $file = $request->file('arquivo_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cursos', $filename, 'public');
            $validated['arquivo_pdf'] = $filename;
        }

        $validated['ativo'] = $validated['ativo'] ?? true;
        $validated['ordem'] = $validated['ordem'] ?? 0;
        $curso = Curso::create($validated);

        if ($request->filled('palestrantes')) {
            $curso->palestrantes()->sync($request->palestrantes);
        }

        return redirect('/admin/cursos')->with('success', 'Curso criado com sucesso.');
    }

    public function adminEdit(Request $request, $id)
    {
        $curso        = Curso::with('palestrantes')->findOrFail($id);
        $palestrantes = Palestrante::where('ativo', true)->orderBy('nome')->get();

        $q       = $request->input('q_aluno');
        $sortMap = ['nome_completo', 'cidade', 'estado'];
        $sort    = in_array($request->input('sort_aluno'), $sortMap) ? $request->input('sort_aluno') : 'nome_completo';
        $dir     = $request->input('dir_aluno') === 'desc' ? 'desc' : 'asc';

        $alunosQuery = $curso->alunos()->orderBy($sort, $dir);

        if ($q) {
            $like = '%' . str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $q) . '%';
            $alunosQuery->where(function ($w) use ($like) {
                $w->where('nome_completo', 'like', $like)
                  ->orWhere('cidade', 'like', $like)
                  ->orWhere('estado', 'like', $like);
            });
        }

        $totalAlunos = $curso->alunos()->count();
        $alunos      = $alunosQuery->paginate(15, ['*'], 'page_aluno')->withQueryString();

        return view('admin.cursos.edit', compact('curso', 'palestrantes', 'alunos', 'totalAlunos', 'q', 'sort', 'dir'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after_or_equal:data_inicio',
            'local'       => 'required|string|max:255',
            'topicos'     => 'nullable|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'ativo'       => 'boolean',
            'ordem'       => 'nullable|integer|min:0|max:999',
        ]);

        if ($request->hasFile('arquivo_pdf')) {
            if ($curso->arquivo_pdf) {
                Storage::disk('public')->delete('cursos/' . $curso->arquivo_pdf);
            }
            $file = $request->file('arquivo_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cursos', $filename, 'public');
            $validated['arquivo_pdf'] = $filename;
        }

        $validated['ativo'] = $request->boolean('ativo');
        $curso->update($validated);
        $curso->palestrantes()->sync($request->palestrantes ?? []);

        return redirect('/admin/cursos')->with('success', 'Curso atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        Curso::destroy($id);
        return redirect('/admin/cursos')->with('success', 'Curso deletado com sucesso.');
    }
}
