<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Illuminate\Http\Request;

class MensagemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email',
            'mensagem' => 'required|string',
            'website' => 'honeypot', // honeypot field
        ]);

        Mensagem::create($request->only(['nome', 'email', 'mensagem']));
        return redirect('/contato')->with('success', 'Mensagem enviada com sucesso.');
    }

    public function adminIndex()
    {
        $mensagens = Mensagem::latest()->get();
        return view('admin.mensagens.index', compact('mensagens'));
    }

    public function adminShow($id)
    {
        $mensagem = Mensagem::findOrFail($id);
        $mensagem->update(['lido' => true]);
        return view('admin.mensagens.show', compact('mensagem'));
    }

    public function adminDestroy($id)
    {
        Mensagem::destroy($id);
        return redirect('/admin/mensagens')->with('success', 'Mensagem deletada com sucesso.');
    }
}
