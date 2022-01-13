<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SubcategoryTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('Categorías')
                ->mouseover('Celulares y tablets')
                ->assertSee('Celulares y Smartphones');
        });
    }
}
