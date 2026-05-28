<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Documento;
use App\Models\Mensagem;
use App\Models\SiteConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_curso_create_read_update_delete()
    {
        $curso = Curso::create([
            'titulo' => 'Test Curso',
            'data_inicio' => now(),
            'data_fim' => now()->addDays(2),
            'local' => 'Test Location',
            'ativo' => true,
            'ordem' => 1,
        ]);

        $this->assertNotNull($curso->id);
        $this->assertEquals('Test Curso', $curso->titulo);

        $curso->update(['titulo' => 'Updated Curso']);
        $this->assertEquals('Updated Curso', Curso::find($curso->id)->titulo);

        $curso->delete();
        $this->assertNull(Curso::find($curso->id));
    }

    public function test_documento_create_read_delete()
    {
        $doc = Documento::create([
            'nome' => 'Test Doc',
            'arquivo_pdf' => 'test.pdf',
            'ativo' => true,
        ]);

        $this->assertTrue(Documento::where('id', $doc->id)->exists());

        $doc->delete();
        $this->assertFalse(Documento::where('id', $doc->id)->exists());
    }

    public function test_mensagem_create_read()
    {
        $msg = Mensagem::create([
            'nome' => 'Tester',
            'email' => 'test@example.com',
            'mensagem' => 'Test message',
            'lido' => false,
        ]);

        $this->assertFalse($msg->lido);
        $msg->update(['lido' => true]);
        $this->assertTrue(Mensagem::find($msg->id)->lido);
    }

    public function test_site_config_create_update()
    {
        $config = SiteConfig::create([
            'chave' => 'test_key',
            'valor' => 'test_value',
        ]);

        $this->assertEquals('test_value', $config->valor);

        SiteConfig::where('chave', 'test_key')->update(['valor' => 'new_value']);
        $this->assertEquals('new_value', SiteConfig::find('test_key')->valor);
    }
}
