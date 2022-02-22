<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\CreateProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\TestHelpers;

class CreateProductsTest extends TestCase
{

    use RefreshDatabase;
    use TestHelpers;
    use DatabaseMigrations;

    /** @test */
    public function it_loads_the_creation_page_of_a_product()
    {
        $user = $this->createAdmin();
        $this->actingAs($user)->get('admin/products/create')
            ->assertOk()
            ->assertSeeLivewire('admin.create-product');
    }

    /** @test */
    public function users_cant_create_products()
    {
        $user = $this->createUser();
        $this->actingAs($user)->get('admin/products/create')
            ->assertForbidden();
    }

    /** @test */
    public function it_redirects_to_login_when_trying_to_create_a_product()
    {
        $this->get('admin/products/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function it_creates_a_product()
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
            ->set('description','dsdsajdoasdj',)
            ->set('price', '118.99',)
            ->set('quantity', '20',)
            ->call('save')
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
