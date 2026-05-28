<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::where('ativo', true)->orderBy('ordem')->orderBy('id')->get();
        return view('documentos.index', compact('documentos'));
    }

    public function adminIndex()
    {
        $documentos = Documento::orderBy('ordem')->orderBy('id')->get();
        return view('admin.documentos.index', compact('documentos'));
    }

    public function adminCreate()
    {
        return view('admin.documentos.create');
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'arquivo_pdf' => 'required|string|max:255',
            'ativo' => 'boolean',
            'ordem' => 'nullable|integer|min:0|max:999',
        ]);

        $validated['ativo'] = $validated['ativo'] ?? true;
        $validated['ordem'] = $validated['ordem'] ?? 0;
        Documento::create($validated);
        return redirect('/admin/documentos')->with('success', 'Documento criado com sucesso.');
    }

    public function adminEdit($id)
    {
        $documento = Documento::findOrFail($id);
        return view('admin.documentos.edit', compact('documento'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'arquivo_pdf' => 'required|string|max:255',
            'ativo' => 'boolean',
            'ordem' => 'nullable|integer|min:0|max:999',
        ]);

        $validated['ativo'] = $validated['ativo'] ?? true;
        $documento->update($validated);
        return redirect('/admin/documentos')->with('success', 'Documento atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        Documento::destroy($id);
        return redirect('/admin/documentos')->with('success', 'Documento deletado com sucesso.');
    }
}
