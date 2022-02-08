<?php

namespace Tests\Browser;

use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);
        $product->images()->create(['url' => 'storage/324234324323423.png']);

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
    public function the_color_products_details_are_shown()
    {
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
            'color' => true
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '1',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Blanco']);

        $product->colors()->attach([1 => ['quantity' => 1]]);

        $this->browse(function (Browser $browser) use($product, $brand) {
            $browser->visit('products/' . $product->id)
                ->assertSee($product->name)
                ->assertSee('Marca: ' . ucfirst($brand->name))
                ->assertPresent('a.underline')
                ->assertSee($product->price)
                ->assertPresent('p.text-2xl')
                ->assertSee('Stock disponible: ' . $product->quantity)
                ->assertPresent('span.font-semibold ')
                ->assertButtonDisabled('+')
                ->assertButtonDisabled('-')
                ->assertButtonDisabled('AGREGAR AL CARRITO DE COMPRAS')
                ->assertSee($product->description)
                ->assertPresent('div.flexslider')
                ->assertPresent('img.flex-active')
                ->screenshot('colorProductDetails-test');
        });
    }

    /** @test */
    public function the_size_color_products_details_are_shown()
    {
        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
            'color' => true, 'size' => true
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '1',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Blanco']);

        $product->colors()->attach([1 => ['quantity' => 1]]);

        $size = Size::create(['name' => 'XL', 'product_id'=>$product->id]);
        $size->colors()
            ->attach([
                1 => ['quantity' => 1]]);

        $this->browse(function (Browser $browser) use($product, $brand) {
            $browser->visit('products/' . $product->id)
                ->assertSee($product->name)
                ->assertSee('Marca: ' . ucfirst($brand->name))
                ->assertPresent('a.underline')
                ->assertSee($product->price)
                ->assertPresent('p.text-2xl')
                ->assertSee('Stock disponible: ' . $product->quantity)
                ->assertPresent('span.font-semibold ')
                ->assertButtonDisabled('+')
                ->assertButtonDisabled('-')
                ->assertButtonDisabled('AGREGAR AL CARRITO DE COMPRAS')
                ->assertSee($product->description)
                ->assertPresent('div.flexslider')
                ->assertPresent('img.flex-active')
                ->screenshot('sizeColorProductDetails-test');
        });
    }

    /** @test */
    public function the_button_limits_are_ok()
    {
        $product = $this->createProduct();
        $product->quantity = '2';
        $product->save();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('products/' . $product->id)
                ->assertButtonDisabled('-')
                ->assertButtonEnabled('+')
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

        $category = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $subcategory = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = $category->brands()->create(['name' => 'LG']);

        $product = Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '5',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use($product){
            $browser->visit('products/' . $product->id)
                ->assertUrlIs('http://localhost:8000/products/' . $product->id)
                ->screenshot('productDetailsAccess-test');
        });


        $category = strtoupper($category->name);
        $this->browse(function (Browser $browser) use($category,$product) {
            $browser->visit('/')
                ->click('@categorias')
                ->assertSee($category)
                ->click('ul.bg-white > li > a')
                ->click('li > article > div.py-4 > h1 > a')
                ->assertUrlIs('http://localhost:8000/products/' . $product->id)
                ->screenshot('productDetailsAccess2-test');
        });
    }

    /** @test */
    public function the_color_and_size_dropdowns_are_shown_according_to_the_chosen_product()
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
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);


        Color::create(['name' => 'Black']);

        $product->colors()->attach([1 => ['quantity' => 10]]);



        $this->get('products/' . $product->id)
            ->assertSeeLivewire('add-cart-item-color');

        $this->browse(function (Browser $browser) use($product) {
            $browser->visit('products/' . $product->id)
                ->assertSee('Color:')
                ->assertPresent('select.form-control')
                ->screenshot('colorDropdown-test');
        });

        $category2 = Category::factory()->create(['name' => 'Moda', 'slug' => Str::slug('Moda'),
            'icon' => '<i class="fas fa-tshirt"></i>']);

        $subcategory2 = Subcategory::create(['category_id' => 1,
            'name' => 'Hombres',
            'slug' => Str::slug('Hombres'),
            'color' => true, 'size' => true
        ]);

        $brand = $category2->brands()->create(['name' => 'GUCCI']);

        $sizeProduct = Product::factory()->create([
            'name' => 'Cinturón Gucci',
            'slug' => Str::slug('Cinturón Gucci'),
            'description' => 'Cinturón Gucci' . ' moderno año 2022',
            'subcategory_id' => $subcategory2->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '5',
            'status' => 2
        ]);


        $sizeProduct->images()->create(['url' => 'storage/324234324323423.png']);

        $size = Size::create(['name' => 'XL', 'product_id'=>$sizeProduct->id]);
        $size->colors()
            ->attach([
                1 => ['quantity' => 10]]);

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

    /** @test */
    public function the_available_stock_of_a_product_changes()
    {
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('products/' . $product->id)
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->press('AGREGAR AL CARRITO')
                ->assertSeeIn('div.items-center > div > p.text-gray-700 > span','Stock disponible:')
                ->assertSeeIn('div.items-center > div > p.text-gray-700', ($product->quantity-2))
                ->screenshot('stockProductChanges-test');
        });
    }

    /** @test */
    public function the_available_stock_of_a_color_product_changes()
    {
        $product = $this->createColorProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Black']);

        $product->colors()->attach([1 => ['quantity' => 20]]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('products/' . $product->id)
                ->click('select.form-control')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(100)
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->pause(100)
                ->press('AGREGAR AL CARRITO')
                ->assertSeeIn('div.items-center > div > p.my-4 > span','Stock disponible:')
                ->assertSee($product->quantity -2)
                ->screenshot('stockColorProductChanges-test');
        });
    }

    /** @test */
    public function the_available_stock_of_a_color_size_product_changes()
    {
        $product = $this->createColorSizeProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Color::create(['name' => 'Black']);

        $product->colors()->attach([1 => ['quantity' => 20]]);

        $size = Size::create(['name' => 'XL', 'product_id'=>$product->id]);
        $size->colors()
            ->attach([
                1 => ['quantity' => 20]]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('products/' . $product->id)
                ->click('div > select')
                ->pause(100)
                ->click('div > select > option:nth-of-type(2)')
                ->pause(1000)
                ->click('div.mt-2 > select')
                ->pause(100)
                ->click('div.mt-2 > select > option:nth-of-type(2)')
                ->pause(100)
                ->click('div.mr-4 > button:nth-of-type(2)')
                ->press('AGREGAR AL CARRITO')
                ->assertSeeIn('div.items-center > div > p.text-gray-700 > span','Stock disponible:')
                ->assertSee(($product->quantity-2))
                ->screenshot('stockColorSizeProductChanges-test');
        });
    }
}
