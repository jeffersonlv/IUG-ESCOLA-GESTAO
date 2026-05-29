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

        $query = Curso::when($qs, fn($q) => $q->where('titulo', 'like', $qs)->orWhere('local', 'like', $qs))
            ->orderBy('ordem')->orderBy('data_inicio');

        if ($qs) {
            $cursos   = $query->get();
            $proximos = collect();
            $passados = collect();
        } else {
            $cursos   = collect();
            $proximos = (clone $query)->whereDate('data_fim', '>=', now()->toDateString())->get();
            $passados = (clone $query)->whereDate('data_fim', '<', now()->toDateString())->orderByDesc('data_fim')->get();
        }

        return view('admin.cursos.index', compact('cursos', 'proximos', 'passados', 'q'));
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

    public function adminEdit($id)
    {
        $curso = Curso::with('palestrantes', 'alunos')->findOrFail($id);
        $palestrantes = Palestrante::where('ativo', true)->orderBy('nome')->get();
        return view('admin.cursos.edit', compact('curso', 'palestrantes'));
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
