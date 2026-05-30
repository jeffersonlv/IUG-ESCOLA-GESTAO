<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CertificadoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('alunos')->orderBy('data_inicio', 'desc')->get();
        return view('admin.certificados.index', compact('cursos'));
    }

    public function imprimir(Request $request)
    {
        $request->validate([
            'nome'   => 'required|string|max:500',
            'titulo' => 'required|string|max:500',
            'data'   => 'required|string|max:200',
            'cidade' => 'required|string|max:200',
            'topico' => 'nullable|string|max:2000',
        ]);

        return view('admin.certificados.imprimir', [
            'nome'   => strip_tags($request->nome),
            'titulo' => strip_tags($request->titulo),
            'data'   => strip_tags($request->data),
            'cidade' => strip_tags($request->cidade),
            'topico' => strip_tags($request->topico ?? ''),
        ]);
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'titulo'     => 'required|string|max:500',
            'data'       => 'required|string|max:200',
            'cidade'     => 'required|string|max:200',
            'topico'     => 'nullable|string|max:2000',
            'curso_slug' => 'required|string|max:200',
            'alunos'     => 'required|array|min:1',
            'alunos.*.nome'        => 'required|string|max:300',
            'alunos.*.cidade_aluno'=> 'nullable|string|max:200',
            'alunos.*.estado_aluno'=> 'nullable|string|max:50',
        ]);

        $this->limparExpirados();

        $titulo    = strip_tags($request->titulo);
        $data      = strip_tags($request->data);
        $cidade    = strip_tags($request->cidade);
        $topico    = strip_tags($request->topico ?? '');
        $cursoSlug = $this->slug($request->curso_slug);

        $fundoPath    = public_path('images/fundoCertificado.jpg');
        $fundoBase64  = file_exists($fundoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fundoPath))
            : '';

        $assPath     = public_path('images/assinatura.png');
        $assBase64   = file_exists($assPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($assPath))
            : '';

        $gerados = [];

        foreach ($request->alunos as $alunoData) {
            $nome        = strip_tags($alunoData['nome']);
            $cidadeAluno = strip_tags($alunoData['cidade_aluno'] ?? '');
            $estadoAluno = strip_tags($alunoData['estado_aluno'] ?? '');

            $nomeArquivo = $this->slug(implode('_', array_filter([$estadoAluno, $cidadeAluno, $nome]))) . '.pdf';
            $caminho     = "public/certificados/{$cursoSlug}/{$nomeArquivo}";

            $html = view('admin.certificados.pdf', [
                'nome'       => $nome,
                'titulo'     => $titulo,
                'data'       => $data,
                'cidade'     => $cidade,
                'topico'     => $topico,
                'fundo'      => $fundoBase64,
                'assinatura' => $assBase64,
            ])->render();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                ->setPaper('a4', 'landscape');

            Storage::put($caminho, $pdf->output());

            $gerados[] = [
                'nome'         => $nome,
                'cidade_aluno' => $cidadeAluno,
                'estado_aluno' => $estadoAluno,
                'arquivo'      => $caminho,
                'url_download' => route('admin.certificados.download', ['file' => $caminho]),
            ];
        }

        return response()->json([
            'ok'         => true,
            'curso_slug' => $cursoSlug,
            'gerados'    => $gerados,
        ]);
    }

    public function download(Request $request)
    {
        $file = $request->query('file');

        if (!$file || !Storage::exists($file)) {
            abort(404);
        }

        // Impedir path traversal
        if (strpos($file, 'public/certificados/') !== 0) {
            abort(403);
        }

        return Storage::download($file, basename($file));
    }

    public function zip(Request $request)
    {
        $cursoSlug = $request->query('curso');
        $cidade    = $request->query('cidade'); // opcional

        if (!$cursoSlug) abort(400);

        $pasta = "public/certificados/{$this->slug($cursoSlug)}";

        if (!Storage::exists($pasta)) {
            abort(404, 'Nenhum certificado gerado para este curso.');
        }

        $arquivos = collect(Storage::files($pasta))->filter(fn($f) => substr($f, -4) === '.pdf');

        if ($cidade) {
            $cidadeSlug = $this->slug($cidade);
            $arquivos = $arquivos->filter(fn($f) => strpos(basename($f), $cidadeSlug) !== false);
        }

        if ($arquivos->isEmpty()) {
            abort(404, 'Nenhum certificado encontrado.');
        }

        $zipNome = $cursoSlug . ($cidade ? '_' . $this->slug($cidade) : '') . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipNome;

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($arquivos as $arquivo) {
            $zip->addFromString(basename($arquivo), Storage::get($arquivo));
        }

        $zip->close();

        return response()->download($zipPath, $zipNome)->deleteFileAfterSend();
    }

    private function limparExpirados()
    {
        $raiz = Storage::files('public/certificados');
        $pastas = Storage::directories('public/certificados');

        foreach ($pastas as $pasta) {
            foreach (Storage::files($pasta) as $arquivo) {
                if (substr($arquivo, -4) === '.pdf') {
                    $modified = Storage::lastModified($arquivo);
                    if (time() - $modified > 30 * 86400) {
                        Storage::delete($arquivo);
                    }
                }
            }
        }
    }

    private function slug(string $str): string
    {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $str = preg_replace('/[^a-zA-Z0-9\s_-]/', '', $str);
        $str = trim(preg_replace('/[\s_-]+/', '_', $str), '_');
        return strtolower($str);
    }
}
