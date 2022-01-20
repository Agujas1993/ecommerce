<?php

namespace Tests\Browser;

use App\Models\Category;
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

    /** @test */
    public function it_is_possible_to_access_the_detail_view_of_a_product()
    {

        $product1 = Category::skip(1)->first()->products()->first();

        $this->browse(function (Browser $browser) use($product1){
            $browser->visit('products/' . $product1->id)
                ->assertUrlIs('http://localhost:8000/products/' . $product1->id)
                ->screenshot('productDetailsAccess-test');
        });


        $product2 = Category::skip(2)->first()->products()->first();

        $this->browse(function (Browser $browser) use($product2) {
            $browser->visit('/')
                ->click('@categorias')
                ->assertSee('Consola y videojuegos')
                ->click('ul.bg-white > li:nth-of-type(3) > a')
                ->click('li:nth-of-type(2) > article > div.py-4 > h1 > a')
                ->assertUrlIs('http://localhost:8000/products/' . $product2->id)
                ->screenshot('productDetailsAccess2-test');
        });

        /*$product3 = Category::first()->products()->first();

        $this->browse(function (Browser $browser) use($product3) {
            $browser->visit('/')
                ->click('div.min-h-screen > main > div.container-menu > section.mb-6 > div:nth-type-of(2) > div.glider-contain > ul.glider-1 > div.glider-track > li:nth-of-type(2) > article > div.py-4 > h1.text-lg > a')
                ->assertUrlIs('http://localhost:8000/products/' . $product3->id)
                ->screenshot('productDetailsAccess3-test');
        });*/
    }
}
