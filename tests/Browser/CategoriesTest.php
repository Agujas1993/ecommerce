<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Psy\Util\Str;
use Tests\DuskTestCase;

class CategoriesTest extends DuskTestCase
{

    /** @test */
    public function it_shows_the_categories()
    {
        $category1 = Category::first()->name;
        $category2 = Category::skip(1)->first()->name;
        $category3 = Category::skip(2)->first()->name;
        $category4 = Category::skip(3)->first()->name;
        $category5 = Category::skip(4)->first()->name;

        $this->browse(function (Browser $browser) use($category1, $category2, $category3, $category4, $category5){
            $browser->visit('/')
                    ->assertSee('Categorías')
                    ->click('@categorias')
                ->assertSee($category1)
                ->assertSee($category2)
                ->assertSee($category3)
                ->assertSee($category4)
                ->assertSee($category5)
                    ->screenshot('categories-test');
        });
    }

    /** @test */
    public function it_shows_the_categories_details()
    {

        $category = Category::first();
        $categoryTitle = $category->name;
        $subcategory1 = $category->subcategories()->first();
        $subcategory2 = $category->subcategories()->skip(1)->first();
        $subcategory3 = $category->subcategories()->skip(2)->first();
        $product1 = $category->products()->first();
        $product2 = $category->products()->skip(1)->first();
        $product3 = $category->products()->skip(1)->first();
        $brand1 = $category->brands()->first();
        $brand2 = $category->brands()->skip(1)->first();
        $brand3 = $category->brands()->skip(2)->first();
        $brand4 = $category->brands()->skip(3)->first();


        $this->browse(function (Browser $browser) use($categoryTitle,$subcategory1,
            $subcategory2,$subcategory3, $product1,$product2, $product3,$brand1,
            $brand2, $brand3, $brand4){

            $browser->visit('/')
                ->assertSee(strtoupper($categoryTitle))
                ->assertSee('Ver más')
                ->click('.text-orange-500')
                ->assertSee($categoryTitle)
                ->assertSee('Subcategorías')
                ->assertSeeIn('aside',ucwords($subcategory1->name))
                ->assertSeeIn('aside',ucwords($subcategory2->name))
                ->assertSeeIn('aside',ucwords($subcategory3->name))
                ->assertSeeIn('aside','Marcas')
                ->assertSeeIn('aside',ucfirst($brand1->name))
                ->assertSeeIn('aside',ucfirst($brand2->name))
                ->assertSeeIn('aside',ucfirst($brand3->name))
                ->assertSeeIn('aside',ucfirst($brand4->name))
                ->assertSeeIn('aside','ELIMINAR FILTROS')
                ->assertSee($product1->name)
                ->assertSee($product2->name)
                ->assertSee($product3->name)
                ->assertSee($product1->price)
                ->assertSee($product2->price)
                ->assertSee($product3->price)
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
        $category = Category::first();
        $product1 = $category->first()->products()->first();
        $product2 = $category->first()->products()->skip(1)->first();
        $product3 = $category->first()->products()->skip(2)->first();
        $product4 = $category->first()->products()->skip(3)->first();
        $product5 = $category->first()->products()->skip(4)->first();

        $categoryTitle = strtoupper($category->name);


        $this->browse(function (Browser $browser) use ($categoryTitle, $product1, $product2, $product3, $product4, $product5) {
            $browser->visit('/')
                ->assertSee($categoryTitle)
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
        $category = Category::first();
        $product1 = $category->first()->products()->first();
        $product2 = $category->first()->products()->skip(1)->first();
        $product3 = $category->first()->products()->skip(2)->first();
        $product4 = $category->first()->products()->skip(3)->first();
        $product5 = $category->first()->products()->skip(4)->first();
        $product6 = $category->first()->products()->skip(5)->first();
        $product7 = $category->first()->products()->skip(6)->first();

        $subcategory = $category->subcategories()->first()->id;
        $brand = $category->brands()->first()->id;

        $product6->status = 1;
        $product6->save();

        $product7->status = 1;
        $product7->save();

        /*$product8 = Product::factory()->create([
            'name' => 'Xbox',
            'slug' => 'xbox',
            'description' => 'xbox 512GB',
            'subcategory_id' => $subcategory,
            'brand_id' => $brand,
            'price' => '262.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product9 = Product::factory()->create([
            'name' => 'Playstation',
            'slug' => 'Playstation',
            'description' => 'Playstation 1TB',
            'subcategory_id' => $subcategory,
            'brand_id' => $brand,
            'price' => '299.99',
            'quantity' => '20',
            'status' => 1
        ]);

        $product8->save();

        $product9->save();
        */
        $category = strtoupper($category->name);

        $this->browse(function (Browser $browser) use ($category, $product1, $product2,
            $product3, $product4, $product5, $product6, $product7) {
            $browser->visit('/')
                ->assertSee($category)
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

    /** @test */
    public function it_filters_by_subcategories()
    {
        $category = Category::first();
        $subcategory1 = $category->subcategories()->first();
        $categoryTitle = $category->slug;
        $subcategory2 = $category->subcategories()->skip(1)->first();

        $this->browse(function (Browser $browser) use($categoryTitle,$subcategory1,
            $subcategory2){
            $browser->visit('/categories/' . $categoryTitle)
                ->click('li > a.cursor-pointer')
                ->assertSee($subcategory1->products()->first()->name)
                ->assertSee($subcategory1->products()->skip(1)->first()->name)
                ->assertDontSee($subcategory2->products()->first()->name)
                ->assertDontSee($subcategory2->products()->skip(1)->first()->name)
                ->screenshot('subcategoriesFilter-test');
        });
    }

    /** @test */
    public function it_filters_by_brands()
    {
        $category = Category::first();
        $brand1 = $category->brands()->skip(2)->first();
        $categoryTitle = $category->slug;
        $brand2 = $category->brands()->skip(3)->first();

        $this->browse(function (Browser $browser) use($categoryTitle,$brand1,
            $brand2){
            $browser->visit('/categories/' . $categoryTitle)
                ->click('ul:nth-of-type(2) > li:nth-of-type(3) > a.cursor-pointer')
                ->assertPresent('a.font-semibold')
                ->assertSee($brand1->products()->first()->name)
                ->assertSee($brand1->products()->skip(1)->first()->name)
                ->assertDontSee($brand2->products()->first()->name)
                ->assertDontSee($brand2->products()->skip(1)->first()->name)
                ->screenshot('brandFilter-test');
        });
    }

    /** @test */
    public function it_filters_by_subcategories_and_brands()
    {
        $category = Category::first();
        $subcategory1 = $category->subcategories()->first();
        $subcategory2 = $category->subcategories()->skip(1)->first();
        $brand1 = $category->brands()->skip(2)->first();
        $brand2 = $category->brands()->skip(3)->first();
        $categoryTitle = $category->slug;

        $this->browse(function (Browser $browser) use($categoryTitle,$brand1,
            $brand2, $subcategory1, $subcategory2){
            $browser->visit('/categories/' . $categoryTitle)
                ->click('li > a.cursor-pointer')
                ->click('ul:nth-of-type(2) > li:nth-of-type(3) > a.cursor-pointer')
                ->assertPresent('a.font-semibold')
                ->assertSee($subcategory1->products()->where('brand_id', $brand1->id)->first()->name)
                ->assertSee($subcategory1->products()->where('brand_id', $brand1->id)->skip(1)->first()->name)
                ->assertDontSee($subcategory2->products()->where('brand_id', $brand2->id)->first()->name)
                ->assertDontSee($subcategory2->products()->where('brand_id', $brand2->id)->skip(1)->first()->name)

                ->screenshot('categoryBrandFilter-test');
        });
    }
}
