<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

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

    public function adminIndex()
    {
        $cursos = Curso::orderBy('ordem')->orderBy('data_inicio')->get();
        return view('admin.cursos.index', compact('cursos'));
    }

    public function adminCreate()
    {
        return view('admin.cursos.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'data_inicio' => 'required|datetime',
            'data_fim' => 'required|datetime',
            'local' => 'required|string',
            'folder_pdf' => 'nullable|string',
            'ativo' => 'boolean',
            'ordem' => 'integer',
        ]);

        Curso::create($request->all());
        return redirect('/admin/cursos')->with('success', 'Curso criado com sucesso.');
    }

    public function adminEdit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('admin.cursos.edit', compact('curso'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->update($request->all());
        return redirect('/admin/cursos')->with('success', 'Curso atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        Curso::destroy($id);
        return redirect('/admin/cursos')->with('success', 'Curso deletado com sucesso.');
    }
}
