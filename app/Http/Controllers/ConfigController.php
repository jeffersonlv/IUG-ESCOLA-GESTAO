<?php

namespace App\Http\Controllers;

use App\Models\SiteConfig;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = SiteConfig::all()->pluck('valor', 'chave')->toArray();
        return view('admin.config.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'sobre_texto' => 'nullable|string|max:2000',
            'publico_alvo' => 'nullable|string|max:500',
            'endereco' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'banner_titulo' => 'nullable|string|max:255',
            'banner_subtitulo' => 'nullable|string|max:500',
            'transparencia_texto' => 'nullable|string|max:2000',
        ]);

        foreach ($validated as $chave => $valor) {
            SiteConfig::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor]
            );
        }

        return redirect('/admin/config')->with('success', 'Configurações atualizadas com sucesso.');
    }
}
