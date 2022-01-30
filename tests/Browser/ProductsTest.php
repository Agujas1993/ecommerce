<?php

namespace Tests\Browser;

use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Livewire\Livewire;
use Tests\DuskTestCase;

class ProductsTest extends DuskTestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /** @test */
    public function the_products_details_are_shown()
    {
        $category = Category::skip(2)->first();
        $product = $category->products()->first();
        $brand = $product->brand;

        $this->browse(function (Browser $browser) use($product, $brand) {
            $browser->visit('products/' . $product->id)
                ->assertSee($product->name)
                    ->assertSee('Marca: ' . ucfirst($brand->name))
                    ->assertPresent('a.underline')
                    ->assertSee($product->price)
                    ->assertPresent('p.text-2xl')
                    ->assertSee('Stock disponible: ' . $product->quantity)
                    ->assertPresent('span.font-semibold ')
                    ->assertButtonEnabled('+')
                    ->assertButtonDisabled('-')
                    ->assertButtonEnabled('AGREGAR AL CARRITO DE COMPRAS')
                ->assertSee($product->description)
                ->assertPresent('div.flexslider')
                ->assertPresent('img.flex-active')
                ->screenshot('productDetails-test');
        });
    }

    /** @test */
    public function the_button_limits_are_ok()
    {
        $product1 = Category::skip(1)->first()->products()->skip(1)->first();

        $product1->quantity = 5;
        $product1->save();

        $this->browse(function (Browser $browser) use ($product1) {
            $browser->visit('products/' . $product1->id)
                ->assertButtonDisabled('-')
                ->assertButtonEnabled('+')
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->assertButtonDisabled('+')
                ->assertButtonEnabled('-')
                ->assertButtonEnabled('AGREGAR AL CARRITO DE COMPRAS')
                ->screenshot('buttonLimits-test');
        });
    }

    /** @test */
    public function it_is_possible_to_access_the_detail_view_of_a_product()
    {

        $product1 = Category::skip(1)->first()->products()->skip(1)->first();

        $this->browse(function (Browser $browser) use($product1){
            $browser->visit('products/' . $product1->id)
                ->assertUrlIs('http://localhost:8000/products/' . $product1->id)
                ->screenshot('productDetailsAccess-test');
        });

        $category = Category::skip(2)->first();
        $product2 = $category->products()->first();

        $category = strtoupper($category->name);
        $this->browse(function (Browser $browser) use($category,$product2) {
            $browser->visit('/')
                ->click('@categorias')
                ->assertSee($category)
                ->click('ul.bg-white > li:nth-of-type(3) > a')
                ->click('li:nth-of-type(3) > article > div.py-4 > h1 > a')
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

    /** @test */
    public function the_color_and_size_dropdowns_are_shown_according_to_the_chosen_product()
    {
        $colorCategory = Category::first();
        $product = $colorCategory->products()->first();

        $this->get('products/' . $product->id)
            ->assertSeeLivewire('add-cart-item-color');

        $this->browse(function (Browser $browser) use($product) {
            $browser->visit('products/' . $product->id)
                ->assertSee('Color:')
                ->assertPresent('select.form-control')
                ->screenshot('colorDropdown-test');
        });

        $sizeColorCategory = Category::skip(4)->first();
        $sizeProduct = $sizeColorCategory->products()->first();

        $this->get('products/' . $sizeProduct->id)
            ->assertSeeLivewire('add-cart-item-size');

        $this->browse(function (Browser $browser) use($sizeProduct) {
            $browser->visit('products/' . $sizeProduct->id)
                ->assertSee('Color:')
                ->assertSee('Talla:')
                ->assertPresent('div > select')
                ->assertPresent('div.mt-2 > select')
                ->screenshot('colorSizeDropdown-test');
        });
    }
}
