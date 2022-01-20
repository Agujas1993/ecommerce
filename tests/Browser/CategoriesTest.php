<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriesTest extends DuskTestCase
{

    /** @test */
    public function it_shows_the_categories()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Categorías')
                    ->click('@categorias')
                ->assertSee('Celulares y tablets')
                ->assertSee('TV, audio y video')
                ->assertSee('Consola y videojuegos')
                ->assertSee('Computación')
                ->assertSee('Moda')
                    ->screenshot('categories-test');
        });
    }

    /** @test */
    public function it_shows_the_categories_details()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('CELULARES Y TABLETS')
                ->assertSee('Ver más')
                ->click('.text-orange-500')
                ->assertSee('Celulares y tablets')
                ->assertSee('Subcategorías')
                ->assertSee('Marcas')
                ->assertSee('ELIMINAR FILTROS')
                ->assertSee('€')
                ->assertPresent('img')
                ->assertPresent('h1.text-lg')
                ->assertPresent('p.font-bold')
                ->screenshot('categoriesDetails-test');
        });
    }

    /** @test */
    public function it_shows_at_least_5_products_from_a_category()
    {
        $category3 = Category::skip(2)->first()->name;
        $product1 = Category::skip(2)->first()->products()->first();
        $product2 = Category::skip(2)->first()->products()->skip(1)->first();
        $product3 = Category::skip(2)->first()->products()->skip(2)->first();
        $product4 = Category::skip(2)->first()->products()->skip(3)->first();
        $product5 = Category::skip(2)->first()->products()->skip(4)->first();

        $category3 = strtoupper($category3);

        $this->browse(function (Browser $browser) use ($category3, $product1, $product2, $product3, $product4, $product5) {
            $browser->visit('/')
                ->assertSee($category3)
                ->assertSee('Ver más')
                ->assertSee($product1->name)
                ->assertSee($product2->name)
                ->assertSee($product3->name)
                ->assertSee($product4->name)
                ->assertSee($product5->name)
                ->screenshot('5_products_from_a_category-test');
        });
    }

    /** @test */
    public function it_shows_at_least_5_products_which_are_published_from_a_category()
    {
        $category3 = Category::skip(2)->first();
        $product1 = Category::skip(2)->first()->products()->first();
        $product2 = Category::skip(2)->first()->products()->skip(1)->first();
        $product3 = Category::skip(2)->first()->products()->skip(2)->first();
        $product4 = Category::skip(2)->first()->products()->skip(3)->first();
        $product5 = Category::skip(2)->first()->products()->skip(4)->first();

        $subcategory = $category3->subcategories()->first()->id;
        $brand = $category3->brands()->first()->id;

        $product6 = Product::factory()->create([
            'name' => 'Xbox',
            'slug' => 'xbox',
            'description' => 'xbox 512GB',
            'subcategory_id' => $subcategory,
            'brand_id' => $brand,
            'price' => '262.99',
            'quantity' => '20',
            'status' => 1
        ]);

        $product7 = Product::factory()->create([
            'name' => 'Playstation',
            'slug' => 'Playstation',
            'description' => 'Playstation 1TB',
            'subcategory_id' => $subcategory,
            'brand_id' => $brand,
            'price' => '299.99',
            'quantity' => '20',
            'status' => 1
        ]);

        $category3 = strtoupper($category3->name);

        $this->browse(function (Browser $browser) use ($category3, $product1, $product2, $product3, $product4, $product5, $product6, $product7) {
            $browser->visit('/')
                ->assertSee($category3)
                ->assertSee('Ver más')
                ->assertSee($product1->name)
                ->assertSee($product2->name)
                ->assertSee($product3->name)
                ->assertSee($product4->name)
                ->assertSee($product5->name)
                ->assertDontSee($product6->name)
                ->assertDontSee($product7->name)
                ->screenshot('5_published_products_from_a_category-test');
        });
    }
}
