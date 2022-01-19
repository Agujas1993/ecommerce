<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductsTest extends DuskTestCase
{
    /** @test */
    public function the_products_details_are_shown()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('products/1')
                ->assertPresent('h1.text-xl')
                    ->assertPresent('.text-trueGray-700')
                    ->assertSee('Marca:')
                    ->assertPresent('a.underline')
                    ->assertSee('€')
                    ->assertPresent('p.text-2xl')
                    ->assertSee('Stock disponible:')
                    ->assertPresent('span.font-semibold ')
                    ->assertButtonEnabled('+')
                    ->assertButtonDisabled('-')
                    ->assertButtonEnabled('AGREGAR AL CARRITO DE COMPRAS')
                ->assertPresent('div.flexslider')
                ->assertPresent('img')
                ->screenshot('productDetails-test');
        });
    }

    /** @test */
    public function the_button_limits_are_ok()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('products/23')
                ->assertButtonEnabled('+')
                ->assertButtonDisabled('-')
                ->assertButtonEnabled('AGREGAR AL CARRITO DE COMPRAS')
                ->screenshot('buttonLimits-test');
        });
    }
}
