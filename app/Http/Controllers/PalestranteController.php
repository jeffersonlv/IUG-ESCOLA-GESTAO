<?php

namespace App\Http\Controllers;

use App\Models\Palestrante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PalestranteController extends Controller
{
    public function adminIndex()
    {
        $palestrantes = Palestrante::orderBy('nome')->get();
        return view('admin.palestrantes.index', compact('palestrantes'));
    }

    public function adminCreate()
    {
        return view('admin.palestrantes.create');
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ativo'     => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('palestrantes', $filename, 'public');
            $validated['foto'] = $filename;
        }

        $validated['ativo'] = $validated['ativo'] ?? true;
        Palestrante::create($validated);
        return redirect()->route('admin.palestrantes.index')->with('success', 'Palestrante criado com sucesso.');
    }

    public function adminEdit($id)
    {
        $palestrante = Palestrante::findOrFail($id);
        return view('admin.palestrantes.edit', compact('palestrante'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $palestrante = Palestrante::findOrFail($id);
        $validated = $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ativo'     => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($palestrante->foto) {
                Storage::disk('public')->delete('palestrantes/' . $palestrante->foto);
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('palestrantes', $filename, 'public');
            $validated['foto'] = $filename;
        }

        $validated['ativo'] = $validated['ativo'] ?? true;
        $palestrante->update($validated);
        return redirect()->route('admin.palestrantes.index')->with('success', 'Palestrante atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        $palestrante = Palestrante::findOrFail($id);
        if ($palestrante->foto) {
            Storage::disk('public')->delete('palestrantes/' . $palestrante->foto);
        }
        $palestrante->delete();
        return redirect()->route('admin.palestrantes.index')->with('success', 'Palestrante deletado com sucesso.');
    }
}
