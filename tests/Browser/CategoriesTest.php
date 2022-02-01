<?php

namespace Tests\Browser;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use \Illuminate\Support\Str;


class CategoriesTest extends DuskTestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_categories()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);
        $category2 = Category::factory()->create(['name' => 'TV, audio y video',
            'slug' => Str::slug('TV, audio y video'),
            'icon' => '<i class="fas fa-tv"></i>']);
        $category3 = Category::factory()->create(['name' => 'Consola y videojuegos',
            'slug' => Str::slug('Consola y videojuegos'),
            'icon' => '<i class="fas fa-gamepad"></i>']);
        $category4 = Category::factory()->create(['name' => 'Computación',
            'slug' => Str::slug('Computación'),
            'icon' => '<i class="fas fa-laptop"></i>']);
        $category5 = Category::factory()->create(['name' => 'Moda', 'slug' => Str::slug('Moda'),
            'icon' => '<i class="fas fa-tshirt"></i>']);

        $this->browse(function (Browser $browser) use($category1, $category2, $category3, $category4, $category5){
            $browser->visit('/')
                ->pause(100)
                    ->assertSee('Categorías')
                ->pause(100)
                    ->click('@categorias')
                ->pause(100)
                ->assertSee($category1->name)
                ->pause(100)
                ->assertSee($category2->name)
                ->pause(100)
                ->assertSee($category3->name)
                ->pause(100)
                ->assertSee($category4->name)
                ->pause(100)
                ->assertSee($category5->name)
                    ->screenshot('categories-test');
        });
    }

    /** @test */
    public function it_shows_the_categories_details()
    {
        $category = $this->createCategory();

        $subcategory1 = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Smartwatches',
            'slug' => Str::slug('Smartwatches'),
        ]);

        $subcategory2 = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand1 = $category->brands()->create(['name' => 'LG']);
        $brand2 = $category->brands()->create(['name' => 'Xiaomi']);

        $product1 = Product::factory()->create([
            'name' => 'Tablet LG',
            'slug' => 'tablet-lg',
            'description' => 'tablet lg 4/64',
            'subcategory_id' => $subcategory2->id,
            'brand_id' => $brand1->id,
            'price' => '262.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Smartwatch Xiaomi',
            'slug' => 'smartwatch-xiaomi',
            'description' => 'Smartwatch Xiaomi moderno',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand2->id,
            'price' => '262.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product1->images()->create(['url' => 'storage/324234324323423.png']);
        $product2->images()->create(['url' => 'storage/32234234123.png']);


        $this->browse(function (Browser $browser) use($category,$subcategory1, $subcategory2, $product1,$product2,$brand1, $brand2){

            $browser->visit('/')
                ->assertSee(strtoupper($category->name))
                ->assertSee('Ver más')
                ->click('.text-orange-500')
                ->assertSee($category->name)
                ->assertSee('Subcategorías')
                ->assertSeeIn('aside',ucwords($subcategory1->name))
                ->assertSeeIn('aside',ucwords($subcategory2->name))
                ->assertSeeIn('aside','Marcas')
                ->assertSeeIn('aside',ucfirst($brand1->name))
                ->assertSeeIn('aside',ucfirst($brand2->name))
                ->assertSeeIn('aside','ELIMINAR FILTROS')
                ->assertSee($product1->name)
                ->assertSee($product2->name)
                ->assertSee($product1->price)
                ->assertSee($product2->price)
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
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product1 = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Tablet LG4030',
            'slug' => Str::slug('Tablet LG4030'),
            'description' => 'Tablet LG4030' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '108.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product3 = Product::factory()->create([
            'name' => 'Tablet LG4080',
            'slug' => Str::slug('Tablet LG4080'),
            'description' => 'Tablet LG4080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '98.99',
            'quantity' => '20',
            'status' => 2
            ]);
        $product4 = Product::factory()->create([
            'name' => 'Tablet LG4182',
            'slug' => Str::slug('Tablet LG4182'),
            'description' => 'Tablet LG4182' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '298.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product5 = Product::factory()->create([
            'name' => 'Tablet LG4182',
            'slug' => Str::slug('Tablet LG4182'),
            'description' => 'Tablet LG4182' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '199.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product1->images()->create(['url' => 'storage/324234324323423.png']);
        $product2->images()->create(['url' => 'storage/32234234123.png']);
        $product3->images()->create(['url' => 'storage/324234324323423.png']);
        $product4->images()->create(['url' => 'storage/32234234123.png']);
        $product5->images()->create(['url' => 'storage/324234324323423.png']);

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
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product1 = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Tablet LG4030',
            'slug' => Str::slug('Tablet LG4030'),
            'description' => 'Tablet LG4030' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '108.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product3 = Product::factory()->create([
            'name' => 'Tablet LG4080',
            'slug' => Str::slug('Tablet LG4080'),
            'description' => 'Tablet LG4080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '98.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product4 = Product::factory()->create([
            'name' => 'Tablet LG4182',
            'slug' => Str::slug('Tablet LG4182'),
            'description' => 'Tablet LG4182' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '298.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product5 = Product::factory()->create([
            'name' => 'Tablet LG4182',
            'slug' => Str::slug('Tablet LG4182'),
            'description' => 'Tablet LG4182' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '199.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product6 = Product::factory()->create([
            'name' => 'Tablet LG4019',
            'slug' => Str::slug('Tablet LG4019'),
            'description' => 'Tablet LG4019' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '199.99',
            'quantity' => '20',
            'status' => 1
            ]);
        $product7 = Product::factory()->create([
            'name' => 'Tablet LG8519',
            'slug' => Str::slug('Tablet LG8519'),
            'description' => 'Tablet LG8519' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '199.99',
            'quantity' => '20',
            'status' => 1
        ]);

        $product1->images()->create(['url' => 'storage/324234324323423.png']);
        $product2->images()->create(['url' => 'storage/32234234123.png']);
        $product3->images()->create(['url' => 'storage/324234324323423.png']);
        $product4->images()->create(['url' => 'storage/32234234123.png']);
        $product5->images()->create(['url' => 'storage/324234324323423.png']);
        $product6->images()->create(['url' => 'storage/324234324323423.png']);
        $product7->images()->create(['url' => 'storage/324234324323423.png']);

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
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $subcategory2 = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Celulares',
            'slug' => Str::slug('Celulares'),
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product1 = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Móvil LG4030',
            'slug' => Str::slug('Móvil LG4030'),
            'description' => 'Móvil LG4030' . 'moderno año 2022',
            'subcategory_id' => $subcategory2->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $categoryTitle = $category->slug;


        $product1->images()->create(['url' => 'storage/324234324323423.png']);
        $product2->images()->create(['url' => 'storage/32234234123.png']);

        $this->browse(function (Browser $browser) use($categoryTitle,$subcategory1,
            $subcategory2, $product1, $product2){
            $browser->visit('/categories/' . $categoryTitle)
                ->click('li > a.cursor-pointer')
                ->assertSee($product1->name)
                ->assertDontSee($product2->name)
                ->screenshot('subcategoriesFilter-test');
        });
    }

    /** @test */
    public function it_filters_by_brands()
    {

        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand1 = $category->brands()->create(['name' => 'LG']);
        $brand2 = $category->brands()->create(['name' => 'Xiaomi']);
        $categoryTitle = $category->slug;

        $product1 = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand1->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Móvil Xiaomi redmi note 8',
            'slug' => Str::slug('Móvil Xiaomi redmi note 8'),
            'description' => 'Móvil Xiaomi redmi note 8' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand2->id,
            'price' => '148.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product1->images()->create(['url' => 'storage/324234324323423.png']);
        $product2->images()->create(['url' => 'storage/32234234123.png']);

        $this->browse(function (Browser $browser) use($categoryTitle, $product1, $product2){
            $browser->visit('/categories/' . $categoryTitle)
                ->click('ul:nth-of-type(2) > li > a.cursor-pointer')
                ->assertPresent('a.font-semibold')
                ->assertSee($product1->name)
                ->assertDontSee($product2->name)
                ->screenshot('brandFilter-test');
        });
    }

    /** @test */
    public function it_filters_by_subcategories_and_brands()
    {
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $subcategory2 = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Celulares',
            'slug' => Str::slug('Celulares'),
        ]);
        $brand1 = $category->brands()->create(['name' => 'LG']);
        $brand2 = $category->brands()->create(['name' => 'Xiaomi']);
        $categoryTitle = $category->slug;

        $product1 = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand1->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Móvil Xiaomi redmi note 8',
            'slug' => Str::slug('Móvil Xiaomi redmi note 8'),
            'description' => 'Móvil Xiaomi redmi note 8' . 'moderno año 2022',
            'subcategory_id' => $subcategory2->id,
            'brand_id' => $brand1->id,
            'price' => '148.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product1->images()->create(['url' => 'storage/324234324323423.png']);
        $product2->images()->create(['url' => 'storage/32234234123.png']);

        $this->browse(function (Browser $browser) use($categoryTitle,$product1, $product2){
            $browser->visit('/categories/' . $categoryTitle)
                ->click('li > a.cursor-pointer')
                ->click('ul:nth-of-type(2) > li:nth-of-type(1) > a.cursor-pointer')
                ->assertPresent('a.font-semibold')
                ->assertSee($product1->name)
                ->assertDontSee($product2->name)
                ->screenshot('categoryBrandFilter-test');
        });
    }
}
