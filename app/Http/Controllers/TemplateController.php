<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::orderBy('tipo')->orderBy('nome')->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'          => 'required|in:flyer,certificado',
            'nome'          => 'required|string|max:255',
            'fundo'         => 'nullable|file|image|max:10240',
            'largura_mm'    => 'required|integer|min:50|max:1000',
            'altura_mm'     => 'required|integer|min:50|max:1000',
            'orientacao'    => 'required|in:portrait,landscape',
            'layout'        => 'nullable|json',
            'ativo'         => 'boolean',
        ]);

        if ($request->hasFile('fundo')) {
            $file = $request->file('fundo');
            $filename = 'template_' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('templates', $filename, 'public');
            $validated['fundo'] = 'templates/' . $filename;
        }

        $validated['layout'] = $request->input('layout')
            ? json_decode($request->input('layout'), true)
            : ['blocks' => []];
        $validated['ativo'] = $request->boolean('ativo');

        Template::create($validated);

        return redirect()->route('admin.templates.index')->with('success', 'Template criado.');
    }

    public function edit($id)
    {
        $template = Template::findOrFail($id);
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'fundo'         => 'nullable|file|image|max:10240',
            'largura_mm'    => 'required|integer|min:50|max:1000',
            'altura_mm'     => 'required|integer|min:50|max:1000',
            'orientacao'    => 'required|in:portrait,landscape',
            'layout'        => 'nullable|json',
            'ativo'         => 'boolean',
        ]);

        if ($request->hasFile('fundo')) {
            if ($template->fundo && Storage::disk('public')->exists($template->fundo)) {
                Storage::disk('public')->delete($template->fundo);
            }
            $file = $request->file('fundo');
            $filename = 'template_' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('templates', $filename, 'public');
            $validated['fundo'] = 'templates/' . $filename;
        }

        if ($request->input('layout')) {
            $validated['layout'] = json_decode($request->input('layout'), true);
        }

        $validated['ativo'] = $request->boolean('ativo');
        $template->update($validated);

        return redirect()->route('admin.templates.index')->with('success', 'Template atualizado.');
    }

    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        if ($template->fundo && Storage::disk('public')->exists($template->fundo)) {
            Storage::disk('public')->delete($template->fundo);
        }
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Template deletado.');
    }

    public function preview($id)
    {
        $template = Template::findOrFail($id);

        $dadosFake = $this->dadosFakeParaTipo($template->tipo);
        $html = $this->renderTemplateHTML($template, $dadosFake);
        $pdf = Pdf::loadHTML($html)->setPaper(
            $template->orientacao === 'landscape' ? 'a4' : 'a4',
            $template->orientacao
        );

        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="preview.pdf"');
    }

    private function renderTemplateHTML(Template $template, array $dados): string
    {
        $blocks = $template->layout['blocks'] ?? [];

        $css = $this->gerarCSS($template);
        $html = '<html><head><meta charset="utf-8"><style>' . $css . '</style></head><body>';

        usort($blocks, fn($a, $b) => ($a['z'] ?? 0) <=> ($b['z'] ?? 0));

        foreach ($blocks as $block) {
            if ($block['tipo'] === 'texto') {
                $html .= $this->renderBlockTexto($block);
            } elseif ($block['tipo'] === 'imagem') {
                $html .= $this->renderBlockImagem($block);
            } elseif ($block['tipo'] === 'campo') {
                $html .= $this->renderBlockCampo($block, $dados);
            }
        }

        $html .= '</body></html>';
        return $html;
    }

    private function renderBlockTexto(array $block): string
    {
        $style = $this->blocoCSSStyle($block);
        $content = htmlspecialchars($block['conteudo'] ?? '');
        return "<div style=\"{$style}\">$content</div>";
    }

    private function renderBlockImagem(array $block): string
    {
        $style = $this->blocoCSSStyle($block);
        $img = $block['imagem'] ?? '';
        $base64 = '';

        if ($img) {
            $path = strpos($img, '/') === 0
                ? public_path(ltrim($img, '/'))
                : public_path($img);
            if (is_file($path)) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
                $base64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        if (!$base64) return '';
        return "<div style=\"{$style}\"><img src=\"$base64\" style=\"width:100%;height:100%;object-fit:cover;\"></div>";
    }

    private function renderBlockCampo(array $block, array $dados): string
    {
        $style = $this->blocoCSSStyle($block);
        $campo = $block['campo'] ?? '';
        $conteudo = $this->interpolarCampo($campo, $dados);

        return "<div style=\"{$style}\">" . htmlspecialchars($conteudo) . '</div>';
    }

    private function interpolarCampo(string $campo, array $dados): string
    {
        return preg_replace_callback('/\{\{(\w+)\}\}/', function ($m) use ($dados) {
            return $dados[$m[1]] ?? '';
        }, $campo);
    }

    private function blocoCSSStyle(array $block): string
    {
        $x = ($block['x_mm'] ?? 0);
        $y = ($block['y_mm'] ?? 0);
        $w = ($block['w_mm'] ?? 50);
        $h = ($block['h_mm'] ?? 20);

        $style = "position:absolute; left:{$x}mm; top:{$y}mm; width:{$w}mm; height:{$h}mm;";

        if ($block['tipo'] === 'texto') {
            $style .= 'overflow:hidden; white-space:pre-wrap; word-wrap:break-word;';
            $style .= 'font-size:' . ($block['font_size'] ?? 12) . 'pt;';
            $style .= 'font-family:' . ($block['font_family'] ?? 'DejaVu Sans') . ';';
            $style .= 'color:' . ($block['color'] ?? '#000') . ';';
            $style .= 'text-align:' . ($block['align'] ?? 'left') . ';';
            if ($block['bold'] ?? false) $style .= 'font-weight:bold;';
            if ($block['italic'] ?? false) $style .= 'font-style:italic;';
        }

        return $style;
    }

    private function gerarCSS(Template $template): string
    {
        $w = $template->largura_mm;
        $h = $template->altura_mm;

        return <<<CSS
            @page { margin: 0; }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                width: {$w}mm;
                height: {$h}mm;
                position: relative;
                font-family: 'DejaVu Sans', Arial, sans-serif;
            }
        CSS;
    }

    private function dadosFakeParaTipo(string $tipo): array
    {
        if ($tipo === 'flyer') {
            return [
                'titulo' => 'Seminário de Exemplo',
                'numero_seminario' => '2026',
                'data_inicio' => '01/06/2026',
                'data_fim' => '03/06/2026',
                'local' => 'São Paulo - SP',
                'publico_alvo' => 'Profissionais e Estudantes',
                'investimento' => 'R$ 500,00',
                'carga_horaria' => '24 horas',
                'programacao' => 'Dia 1: Abertura e Palestra Inicial',
                'folder_palestrantes' => 'Prof. João Silva',
            ];
        } else {
            return [
                'nome_aluno' => 'Maria da Silva',
                'titulo_curso' => 'Curso de Especialização',
                'data_curso' => '01 a 03 de junho de 2026',
                'cidade_curso' => 'São Paulo',
                'topicos' => 'Tópico 1, Tópico 2, Tópico 3',
            ];
        }
    }
}
