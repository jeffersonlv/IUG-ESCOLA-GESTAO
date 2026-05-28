<?php

namespace Tests\Feature\Models;

use App\Models\SiteConfig;
use Tests\TestCase;

class SiteConfigTest extends TestCase
{
    public function test_fillable_attributes()
    {
        $fillable = ['chave', 'valor'];
        $model = new SiteConfig();
        $this->assertEquals($fillable, $model->getFillable());
    }

    public function test_no_timestamps()
    {
        $model = new SiteConfig();
        $this->assertFalse($model->timestamps);
    }

    public function test_string_primary_key()
    {
        $model = new SiteConfig();
        $this->assertFalse($model->getIncrementing());
        $this->assertEquals('string', $model->getKeyType());
    }

}
