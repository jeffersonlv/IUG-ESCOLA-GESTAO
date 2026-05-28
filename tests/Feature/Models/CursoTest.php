<?php

namespace Tests\Feature\Models;

use App\Models\Curso;
use Tests\TestCase;

class CursoTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $fillable = ['titulo', 'data_inicio', 'data_fim', 'local', 'topicos', 'folder_pdf', 'ativo', 'ordem'];
        $model = new Curso();
        $this->assertEquals($fillable, $model->getFillable());
    }

    public function test_casts()
    {
        $model = new Curso();
        $casts = $model->getCasts();
        $this->assertArrayHasKey('data_inicio', $casts);
        $this->assertArrayHasKey('data_fim', $casts);
        $this->assertArrayHasKey('ativo', $casts);
    }

}
