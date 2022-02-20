<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\EditProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\TestHelpers;

class EditProductsTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;
    use DatabaseMigrations;

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
        $this->withoutExceptionHandling();
        $product = $this->createProduct();
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(EditProduct::class)
            ->set($product->subcategory->category_id, $category->id)
            ->set($product->name, 'algo')
            ->set($product->slug, 'algo')
            ->set($product->subcategory_id,$subcategory->id)
            ->set($product->brand->id, $brand->id)
            ->set($product->description,'dsdsajdoasdj',)
            ->set($product->price, '118.99',)
            ->set($product->quantity, '20',)
            ->call('save')
            ->assertRedirect('admin/products/1/edit');
        $this->assertEquals(1,Product::count());
    }

    /** @test */
    public function it_deletes_a_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(EditProduct::class)
            ->set('category_id', $category->id)
            ->set('name', 'algo')
            ->set('slug', 'algo')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','dsdsajdoasdj',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('delete')
            ->assertRedirect('admin/products/1/edit');
        $this->assertEquals(1,Product::count());
    }

    /** @test */
    public function the_category_id_is_required()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', '')
            ->set('name', 'algo')
            ->set('slug', 'algo')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','dsdsajdoasdj',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('save')
            ->assertHasErrors(['category_id']);
        $this->assertDatabaseEmpty('products');
    }

    /** @test */
    public function the_subcategory_id_is_required()
    {
        $category = $this->createCategory();
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('name', 'algo')
            ->set('slug', 'algo')
            ->set('subcategory_id', '')
            ->set('brand_id', $brand->id)
            ->set('description','dsdsajdoasdj',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('save')
            ->assertHasErrors(['subcategory_id']);
        $this->assertDatabaseEmpty('products');
    }

    /** @test */
    public function the_name_is_required()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('name', '')
            ->set('slug', 'algo')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','dsdsajdoasdj',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('save')
            ->assertHasErrors(['name']);
        $this->assertDatabaseEmpty('products');
    }

    /** @test */
    public function the_slug_is_required()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('name', 'algo')
            ->set('slug', '')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','dsdsajdoasdj',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('save')
            ->assertHasErrors(['slug']);
        $this->assertDatabaseEmpty('products');
    }

    /** @test */
    public function the_description_is_required()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('name', 'algo')
            ->set('slug', 'algo')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('save')
            ->assertHasErrors(['description']);
        $this->assertDatabaseEmpty('products');
    }

    /** @test */
    public function the_price_is_required()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('name', 'algo')
            ->set('slug', 'algo')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','dsdsdsad',)
            ->set('price', '',)
            ->set('quantity', '20',)
            ->call('save')
            ->assertHasErrors(['price']);
        $this->assertDatabaseEmpty('products');
    }

    /** @test */
    public function the_quantity_is_required()
    {
        $category = $this->createCategory();
        $subcategory = $this->createCustomSubcategory($category->id,'celulares');
        $brand = $category->brands()->create(['name' => 'LG']);

        Livewire::test(CreateProduct::class)
            ->set('category_id', $category->id)
            ->set('name', 'algo')
            ->set('slug', 'algo')
            ->set('subcategory_id',$subcategory->id)
            ->set('brand_id', $brand->id)
            ->set('description','dsdsdsad',)
            ->set('price', '118.99',)
            ->set('quantity', '',)
            ->call('save')
            ->assertHasErrors(['quantity']);
        $this->assertDatabaseEmpty('products');
    }
}
