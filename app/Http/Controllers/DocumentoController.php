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
        $request->validate([
            'nome' => 'required|string',
            'arquivo_pdf' => 'required|string',
            'ativo' => 'boolean',
            'ordem' => 'integer',
        ]);

        Documento::create($request->all());
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
        $documento->update($request->all());
        return redirect('/admin/documentos')->with('success', 'Documento atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        Documento::destroy($id);
        return redirect('/admin/documentos')->with('success', 'Documento deletado com sucesso.');
    }
}
