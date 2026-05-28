<?php

namespace Tests\Feature\Models;

use App\Models\Mensagem;
use Tests\TestCase;

class MensagemTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $fillable = ['nome', 'email', 'mensagem', 'lido'];
        $model = new Mensagem();
        $this->assertEquals($fillable, $model->getFillable());
    }

    public function test_casts()
    {
        $model = new Mensagem();
        $casts = $model->getCasts();
        $this->assertArrayHasKey('lido', $casts);
    }

}
