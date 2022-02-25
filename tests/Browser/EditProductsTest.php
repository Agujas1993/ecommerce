<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestHelpers;

class EditProductsTest extends DuskTestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use TestHelpers;

    /** @test */
    public function it_loads_the_edition_page_of_a_product()
    {
        $product = $this->createProduct();

        $user = $this->createAdmin();
        $this->actingAs($user)->get('admin/products/' . $product->id .'/edit')
            ->assertOk()
            ->assertSeeLivewire('admin.edit-product');
    }

    /** @test */
    public function it_edits_a_product()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tv')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
                ->assertSeeIn('#actualizado','Actualizado')
                ->screenshot('itEditsProduct-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function it_edits_a_color_product()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createColorSubcategory();
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createColorProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tablet')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99')
                ->click('div.grid > label > input')
                ->type('.quantityColor', '2')
                ->pause(100)
                ->press('AGREGAR')
                ->pause(200)
                ->assertSeeIn('#agregado','Agregado')
                ->screenshot('itEditsColorProduct-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function it_edits_a_size_color_product()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createColorSizeSubcategory();
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createColorSizeProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tablet')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99')
                ->click('div.grid > label > input')
                ->type('#talla', 'XXS')
                ->pause(100)
                ->press('AGREGAR')
                ->pause(200)
                ->assertSee('XXS')
                ->screenshot('itEditsColorSizeProduct-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_size_of_a_color_size_product_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createColorSizeSubcategory();
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createColorSizeProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tablet')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99')
                ->click('div.grid > label > input')
                ->pause(100)
                ->press('AGREGAR')
                ->pause(200)
                ->assertSeeIn('#errorSize','El campo name es obligatorio.')
                ->screenshot('itEditsColorSizeProduct-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_color_of_a_color_size_product_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createColorSizeSubcategory();
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createColorSizeProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tablet')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99')
                ->pause(100)
                ->press('AGREGAR')
                ->pause(100)
                ->assertSeeIn('#errorColorSize','El campo color id es obligatorio.')
                ->screenshot('colorOfAColorSizeProductIsRequired-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_color_of_a_color_product_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createColorSubcategory();
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createColorProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tablet')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99')
                ->pause(100)
                ->press('AGREGAR')
                ->pause(100)
                ->assertSeeIn('#errorColor','El campo color id es obligatorio.')
                ->screenshot('colorOfAColorProductIsRequired-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_quantity_of_a_color_product_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createColorSubcategory();
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createColorProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tablet')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99')
                ->click('div.grid > label > input')
                ->pause(100)
                ->press('AGREGAR')
                ->pause(100)
                ->assertSeeIn('#errorQuantity','El campo quantity es obligatorio.')
                ->screenshot('quantityOfAColorProductIsRequired-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function it_deletes_a_product()
    {
        $user = $this->createAdmin();
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use($user, $product) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->press('ELIMINAR')
                ->press('Yes, delete it!')
                ->pause(100)
                ->assertPathIs('/admin')
                ->screenshot('itDeletesProduct-test');
            $this->assertEquals(0, Product::count());
        });
    }

    /** @test */
    public function the_category_can_stay_the_same()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();


        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', '')
                ->pause(100)
                ->type('.name', 'Tv')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
                ->assertSeeIn('#actualizado','Actualizado')
                ->screenshot('theCategoryCanStayTheSame-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_subcategory_can_stay_the_same()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use($user, $product, $category, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tv')
                ->pause(100)
                ->select('subcategory', '')
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
                ->assertSeeIn('#actualizado','Actualizado')
                ->screenshot('theSubcategoryCanStayTheSame-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_brand_can_stay_the_same()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use($user, $product, $subcategory, $category, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'Tv')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', ' ')
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
                ->assertSeeIn('#actualizado','Actualizado')
                ->screenshot('theBrandCanStayTheSame-test');
            $this->assertEquals(1, Product::count());
        });
    }

    /** @test */
    public function the_name_and_slug_are_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', ' ')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
                ->assertSeeIn('#product','El campo name es obligatorio.')
                ->pause(100)
                ->assertSeeIn('.slug','El campo slug es obligatorio.')
                ->screenshot('theNameAndSlugAreRequired-test');
        });
    }

    /** @test */
    public function the_description_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'sa')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', '110.99',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
                ->assertSeeIn('.description','El campo description es obligatorio.')
                ->screenshot('theDescriptionIsRequired-test');
        });
    }

    /** @test */
    public function the_price_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'sa')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->select('brand', $brand->id)
                ->value('.description', 'dsdsajdoasdj',)
                ->value('#price', ' ',)
                ->value('#quantity', '10',)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(200)
                ->assertSeeIn('.priceError','El campo price es obligatorio.')
                ->screenshot('thePriceIsRequired-test');
        });
    }

    /** @test */
    public function the_quantity_is_required()
    {
        $user = $this->createAdmin();
        $category = $this->createCustomCategory('TV');
        $subcategory = $this->createCustomSubcategory($category->id,'tvs');
        $brand = $category->brands()->create(['name' => 'Xiaomi']);
        $product = $this->createProduct();
        $product->quantity = ' ';

        $this->browse(function (Browser $browser) use($user, $product, $category, $subcategory, $brand) {

            $browser->loginAs($user)->visit('admin/products/' . $product->id . '/edit')
                ->select('category', $category->id)
                ->pause(100)
                ->type('.name', 'sa')
                ->pause(100)
                ->select('subcategory', $subcategory->id)
                ->pause(100)
                ->select('brand', $brand->id)
                ->pause(100)
                ->click('input.quantity')
                ->pause(100)
                ->clear('quantity')
                ->pause(100)
                ->press('ACTUALIZAR PRODUCTO')
                ->pause(100)
               ->assertSee('Cantidad')
                ->screenshot('theQuantityIsRequired-test');
        });
    }
}
