<?php

namespace Tests\Feature\Models;

use App\Models\Documento;
use Tests\TestCase;

class DocumentoTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $fillable = ['nome', 'arquivo_pdf', 'ativo', 'ordem'];
        $model = new Documento();
        $this->assertEquals($fillable, $model->getFillable());
    }

    public function test_casts()
    {
        $model = new Documento();
        $casts = $model->getCasts();
        $this->assertArrayHasKey('ativo', $casts);
    }

}
