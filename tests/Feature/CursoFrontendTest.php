<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\SiteConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CursoFrontendTest extends TestCase
{
    use RefreshDatabase;

    // Cursos são exibidos na home (welcome.blade.php), não em /cursos

    private function cursoBase(array $extra = []): array
    {
        return array_merge([
            'titulo'     => 'Curso Teste',
            'data_inicio'=> now(),
            'data_fim'   => now()->addDays(3),
            'local'      => 'Brasília - DF',
            'ativo'      => true,
            'ordem'      => 1,
        ], $extra);
    }

    // ── folder_pdf ────────────────────────────────────────────────────────────

    public function test_curso_com_folder_pdf_mostra_iframe_e_botao_flyer()
    {
        Curso::create($this->cursoBase([
            'folder_pdf' => 'cursos/folders/test_folder.pdf',
        ]));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/storage/cursos/folders/test_folder.pdf', false);
        $response->assertSee('Download Flyer');
    }

    public function test_curso_sem_pdf_nao_mostra_botao()
    {
        Curso::create($this->cursoBase());

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Download Flyer');
    }

    // ── arquivo_pdf como fallback ─────────────────────────────────────────────

    public function test_curso_sem_folder_usa_arquivo_pdf_como_fallback()
    {
        Curso::create($this->cursoBase([
            'arquivo_pdf' => 'arquivo_teste.pdf',
        ]));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/storage/cursos/arquivo_teste.pdf', false);
        $response->assertSee('Download Flyer');
    }

    public function test_folder_pdf_tem_prioridade_sobre_arquivo_pdf()
    {
        Curso::create($this->cursoBase([
            'folder_pdf'  => 'cursos/folders/test_folder.pdf',
            'arquivo_pdf' => 'arquivo_teste.pdf',
        ]));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/storage/cursos/folders/test_folder.pdf', false);
        $response->assertDontSee('/storage/cursos/arquivo_teste.pdf', false);
    }

    // ── path format ───────────────────────────────────────────────────────────

    public function test_url_folder_pdf_usa_prefixo_storage_sem_public()
    {
        $path = 'cursos/folders/folder_abc123.pdf';
        Curso::create($this->cursoBase(['folder_pdf' => $path]));

        $response = $this->get('/');

        $response->assertSee('/storage/' . $path, false);
        $response->assertDontSee('/public/' . $path, false);
    }

    // ── curso inativo não aparece ─────────────────────────────────────────────

    public function test_curso_inativo_nao_aparece_na_home()
    {
        Curso::create($this->cursoBase([
            'ativo'      => false,
            'folder_pdf' => 'cursos/folders/test.pdf',
        ]));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Download Flyer');
    }
}
