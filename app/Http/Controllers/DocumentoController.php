<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::where('ativo', true)
            ->where(function ($q) {
                $q->whereNull('data_vencimento')->orWhere('data_vencimento', '>=', now()->toDateString());
            })
            ->orderBy('ordem')->orderBy('id')->get();
        return view('documentos.index', compact('documentos'));
    }

    public function download($id)
    {
        $documento = Documento::where('ativo', true)->findOrFail($id);
        $path = storage_path('app/public/documentos/' . $documento->arquivo_pdf);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $documento->arquivo_pdf);
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
            'nome'             => 'required|string|max:255',
            'arquivo_pdf'      => 'required|file|mimes:pdf|max:10240',
            'ativo'            => 'boolean',
            'ordem'            => 'nullable|integer|min:0|max:999',
            'data_vencimento'  => 'nullable|date',
        ]);

        if ($request->hasFile('arquivo_pdf')) {
            $file = $request->file('arquivo_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('documentos', $filename, 'public');
            $validated['arquivo_pdf'] = $filename;
        }

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
            'nome'             => 'required|string|max:255',
            'arquivo_pdf'      => 'nullable|file|mimes:pdf|max:10240',
            'ativo'            => 'boolean',
            'ordem'            => 'nullable|integer|min:0|max:999',
            'data_vencimento'  => 'nullable|date',
        ]);

        if ($request->hasFile('arquivo_pdf')) {
            if ($documento->arquivo_pdf) {
                Storage::disk('public')->delete('documentos/' . $documento->arquivo_pdf);
            }
            $file = $request->file('arquivo_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('documentos', $filename, 'public');
            $validated['arquivo_pdf'] = $filename;
        }

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
