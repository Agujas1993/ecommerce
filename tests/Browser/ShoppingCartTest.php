<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShoppingCartTest extends DuskTestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function the_red_circle_of_the_cart_increases_when_adding_a_product()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                    ->assertSeeIn('span.relative > span.absolute','1')
            ->screenshot('redCircleIncreasesWhenAddingProduct-test');
        });
    }

    /** @test */
    public function it_is_possible_to_add_a_color_product()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
            'color' => true
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '1',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Blanco']);

        $product->colors()->attach([1 => ['quantity' => 1]]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('select.form-control')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->assertSeeIn('span.relative > span.absolute','1')
                ->screenshot('addingColorProduct-test');
        });
    }

    /** @test */
    public function it_is_possible_to_add_a_size_color_product()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
            'color' => true,  'size' => true
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '2',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Blanco']);
        Color::create(['name' => 'Negro']);

        $product->colors()->attach([1 => ['quantity' => 1]]);

        $size = Size::create(['name' => 'XL', 'product_id'=>$product->id]);
        $product->sizes()->create(['name' => 'XL']);

        $size->colors()
            ->attach([
                1 => ['quantity' => 1],
                2 => ['quantity' => 1]]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div > select')
                ->pause(100)
                ->select('select', 'XL')
                ->pause(1000)
                ->select('.mt-2 > select', 'Blanco')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->assertSeeIn('span.relative > span.absolute','1')
                ->screenshot('addingSizeColorProduct-test');
        });
    }

    /** @test */
    public function it_shows_the_products_added_to_cart()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->assertSeeIn('li.flex', $product->name)
                ->pause(100)
                ->assertSeeIn('li.flex', $product->price)
                ->screenshot('redCircleIncreasesWhenAddingProduct-test');
        });
    }

    /** @test */
    public function it_is_not_possible_to_add_products_that_are_out_of_stock()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '1',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->assertButtonDisabled('AGREGAR AL CARRITO DE COMPRAS')
                ->screenshot('showCartProducts-test');
        });
    }

    /** @test */
    public function it_is_not_possible_to_add_color_products_that_are_out_of_stock()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
            'color' => true
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '1',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Blanco']);

        $product->colors()->attach([1 => ['quantity' => 1]]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('select.form-control')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->assertButtonDisabled('AGREGAR AL CARRITO DE COMPRAS')
                ->screenshot('outStockColorProducts-test');
        });
    }

    /** @test */
    public function it_is_not_possible_to_add_size_color_products_that_are_out_of_stock()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
            'color' => true,  'size' => true
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '1',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Blanco']);

        $product->colors()->attach([1 => ['quantity' => 1]]);

        $size = Size::create(['name' => 'XL', 'product_id'=>$product->id]);
        $size->colors()
            ->attach([
                1 => ['quantity' => 1]]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div > select')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(1000)
                ->releaseMouse()
                ->click('div.mt-2 > select')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->assertButtonDisabled('AGREGAR AL CARRITO DE COMPRAS')
                ->screenshot('outStockSizeColorProducts-test');
        });
    }

    /** @test */
    public function it_shows_the_products_in_the_cart_view()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);


        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->click('div.px-3 > a.inline-flex')
                ->assertSee('CARRITO DE COMPRAS')
                ->assertSee($product->name)
                ->pause(100)
                ->assertSee($product->price)
                ->pause(100)
                ->assertPathIs('/shopping-cart')
                ->screenshot('cartView-test');
        });
    }

    /** @test */
    public function it_calculates_the_total_when_increasing_the_quantity_in_the_cart_view()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);


        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->click('div.px-3 > a.inline-flex')
                ->assertSee($product->name)
                ->pause(100)
                ->click('div.flex > button:nth-of-type(2)')
                ->pause(100)
                ->assertSeeIn('.text-sm .text-gray-500',$product->price*2 . " €")
                ->pause(100)
                ->screenshot('calculateCartView-test');
        });
    }

    /** @test */
    public function it_is_possible_to_flush_the_shopping_cart()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);


        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->click('div.px-3 > a.inline-flex')
                ->assertSee($product->name)
                ->pause(100)
                ->click('.text-sm .cursor-pointer')
                ->pause(100)
                ->assertSee("TU CARRITO DE COMPRAS ESTÁ VACÍO")
                ->pause(100)
                ->assertSee("IR AL INICIO")
                ->assertPresent('a.inline-flex')
                ->screenshot('flushCartView-test');
        });
    }

    /** @test */
    public function it_is_possible_to_remove_a_product_from_the_shopping_cart()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);


        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->click('div.px-3 > a.inline-flex')
                ->assertSee($product->name)
                ->pause(100)
                ->click('i.fa-trash')
                ->pause(100)
                ->assertSee("TU CARRITO DE COMPRAS ESTÁ VACÍO")
                ->pause(100)
                ->assertSee("IR AL INICIO")
                ->assertPresent('a.inline-flex')
                ->screenshot('removeProductCartView-test');
        });
    }

    /** @test */
    public function it_saves_the_shopping_cart_when_user_logs_out()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category1->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);


        User::factory()->create([
            'name' => 'Samuel Garcia',
            'email' => 'samuel@test.com',
            'password' => bcrypt('123'),
        ]);

        $this->browse(function (Browser $browser) use($product){
            $browser->visit('/login')
                ->pause(100)
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(100)
                ->click('a.mx-6')
                ->pause(100)
                ->click('h1.text-lg > a')
                ->pause(100)
                ->click('div.flex-1 > button')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->assertSeeIn('li.flex', $product->name)
                ->logout()
                ->visit('/login')
                ->pause(100)
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(100)
                ->click('div.relative > div > span')
                ->pause(100)
                ->assertSeeIn('li.flex', $product->name)
                ->screenshot('savesCart-test');
        });
    }
}
