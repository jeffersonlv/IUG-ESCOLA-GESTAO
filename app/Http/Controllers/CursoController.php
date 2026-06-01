<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Palestrante;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $configs      = SiteConfig::all()->pluck('valor', 'chave')->toArray();
        return view('admin.cursos.create', compact('palestrantes', 'configs'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'titulo'        => 'required|string|max:255',
            'data_inicio'   => 'required|date',
            'data_fim'      => 'required|date|after_or_equal:data_inicio',
            'local'         => 'required|string|max:255',
            'investimento'  => 'nullable|string|max:100',
            'carga_horaria' => 'nullable|string|max:50',
            'publico_alvo'  => 'nullable|string',
            'topicos'       => 'nullable|string|max:420',
            'arquivo_pdf'   => 'nullable|file|mimes:pdf|max:10240',
            'ativo'         => 'boolean',
        ]);

        if ($request->hasFile('arquivo_pdf')) {
            $file = $request->file('arquivo_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cursos', $filename, 'public');
            $validated['arquivo_pdf'] = $filename;
        }

        $validated['programacao']         = $this->parseJson($request->input('programacao'));
        $validated['folder_palestrantes'] = $this->resolveFolderPalestrantes($request->input('palestrantes', []));

        $folderGerado = trim($request->input('folder_pdf_gerado', ''));
        if ($folderGerado) {
            $validated['folder_pdf'] = $folderGerado;
        }

        $validated['ativo'] = $validated['ativo'] ?? true;
        $validated['ordem'] = 0;
        $curso = Curso::create($validated);

        $curso->palestrantes()->sync($request->input('palestrantes', []));

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
            'titulo'           => 'required|string|max:255',
            'data_inicio'      => 'required|date',
            'data_fim'         => 'required|date|after_or_equal:data_inicio',
            'local'            => 'required|string|max:255',
            'investimento'     => 'nullable|string|max:100',
            'carga_horaria'    => 'nullable|string|max:50',
            'publico_alvo'     => 'nullable|string',
            'topicos'          => 'nullable|string|max:420',
            'arquivo_pdf'      => 'nullable|file|mimes:pdf|max:10240',
            'flyer_principal'  => 'nullable|in:gerado,upload',
            'ativo'            => 'boolean',
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

        $validated['programacao']         = $this->parseJson($request->input('programacao'));
        $validated['folder_palestrantes'] = $this->resolveFolderPalestrantes($request->input('palestrantes', []));

        $folderGerado = trim($request->input('folder_pdf_gerado', ''));
        if ($folderGerado) {
            $validated['folder_pdf'] = $folderGerado;
        }

        $validated['ativo'] = $request->boolean('ativo');
        $curso->update($validated);
        $curso->palestrantes()->sync($request->input('palestrantes', []));

        return redirect('/admin/cursos')->with('success', 'Curso atualizado com sucesso.');
    }

    public function adminDestroy($id)
    {
        Curso::destroy($id);
        return redirect('/admin/cursos')->with('success', 'Curso deletado com sucesso.');
    }

    public function downloadFlyer($id)
    {
        $curso = Curso::where('ativo', true)->findOrFail($id);

        $principal = $curso->flyer_principal;
        if (!$principal) {
            $principal = $curso->folder_pdf ? 'gerado' : 'upload';
        }

        if ($principal === 'gerado' && $curso->folder_pdf) {
            $pdfPath = storage_path('app/public/' . $curso->folder_pdf);
        } elseif ($principal === 'upload' && $curso->arquivo_pdf) {
            $pdfPath = storage_path('app/public/cursos/' . $curso->arquivo_pdf);
        } elseif ($curso->folder_pdf) {
            $pdfPath = storage_path('app/public/' . $curso->folder_pdf);
        } elseif ($curso->arquivo_pdf) {
            $pdfPath = storage_path('app/public/cursos/' . $curso->arquivo_pdf);
        } else {
            $pdfPath = null;
        }

        if (!$pdfPath || !file_exists($pdfPath)) {
            abort(404);
        }

        $curso->increment('flyer_downloads');

        $filename = \Str::slug($curso->titulo) . '.pdf';
        return response()->download($pdfPath, $filename);
    }

    public function gerarFolderPdf(Request $request)
    {
        $dados = $request->validate([
            'titulo'                  => 'required|string|max:255',
            'data_inicio'             => 'nullable|string|max:50',
            'data_fim'                => 'nullable|string|max:50',
            'local'                   => 'nullable|string|max:255',
            'investimento'            => 'nullable|string|max:100',
            'carga_horaria'           => 'nullable|string|max:50',
            'publico_alvo'            => 'nullable|string',
            'programacao'             => 'nullable|array',
            'folder_palestrante_ids'  => 'nullable|array',
        ]);

        $dados['folder_palestrantes'] = $this->folderPalestrantesComFoto(
            $this->resolveFolderPalestrantes($dados['folder_palestrante_ids'] ?? [])
        );

        $configs = SiteConfig::all()->pluck('valor', 'chave')->toArray();

        $logoPath   = public_path('images/logo.png');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $bgPath   = public_path('images/background_folder.jpg');
        $bgBase64 = file_exists($bgPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath))
            : '';

        $html = view('admin.cursos.folder_pdf', compact('dados', 'configs', 'logoBase64', 'bgBase64'))->render();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $filename = 'folder_' . bin2hex(random_bytes(8)) . '.pdf';
        $caminho  = 'public/cursos/folders/' . $filename;

        Storage::put($caminho, $pdf->output());

        return response()->json([
            'success' => true,
            'path'    => 'cursos/folders/' . $filename,
            'url'     => Storage::url($caminho),
        ]);
    }

    private function resolveFolderPalestrantes($ids): ?array
    {
        if (empty($ids)) return null;
        return Palestrante::whereIn('id', $ids)->orderBy('nome')->get()
            ->map(fn($p) => ['nome' => $p->nome, 'cargo' => $p->descricao ?? '', 'foto' => $p->foto])
            ->values()->all();
    }

    // Converte caminho da foto em data-uri base64 (só no render; nunca persistido no banco)
    private function folderPalestrantesComFoto(?array $lista): ?array
    {
        if (empty($lista)) return $lista;
        return array_map(function ($p) {
            $p['foto'] = $this->fotoBase64($p['foto'] ?? null);
            return $p;
        }, $lista);
    }

    private function fotoBase64(?string $foto): string
    {
        if (!$foto) return '';
        $path = (strpos($foto, '/') === 0)
            ? public_path(ltrim($foto, '/'))
            : storage_path('app/public/palestrantes/' . $foto);
        if (!is_file($path)) return '';
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    private function parseJson(?string $value): ?array
    {
        if (!$value) return null;
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
    }
}
