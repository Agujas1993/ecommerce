<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\TestHelpers;

class ProductsTest extends TestCase
{

    use RefreshDatabase;
    use TestHelpers;

    protected $defaultData = [
        'name' => 'oppo12-lite',
        'slug' => 'oppo-12-lite',
        'description' => 'opo12 512GB',
        'subcategory_id' => '1',
        'brand_id' => '1',
        'price' => '22.99',
        'quantity' => '20',
        'status' => 2
    ];


    /** @test */
    public function it_shows_at_least_5_products_from_a_category()
    {
        $celulares = Category::factory()->create(["name" => "Celulares y tablets",
            "slug" => "celulares-y-tablets",
            "icon" => 'algo',
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);

        $celulares->subcategories()->create([
            'name' => 'Celulares y smartphones',
            'slug' => Str::slug('Celulares y smartphones'),
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
            ]);

        Brand::factory()->create(['id' => '1', 'name' => 'Oppo']);


        $celulares->products()->create($this->withData(['name' => 'Oppo 12']));
        $celulares->products()->create($this->withData(['name' => 'Oppo x2']));
        $celulares->products()->create($this->withData(['name' => 'Oppo s3']));
        $celulares->products()->create($this->withData(['name' => 'Oppo galaxy']));
        $celulares->products()->create($this->withData(['name' => 'Oppo lite']));

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Celulares y tablets')
            ->assertSee('Ver más')
            ->assertSee('Oppo 12')
            ->assertSee('Oppo x2')
            ->assertSee('Oppo s3')
            ->assertSee('Oppo galaxy')
            ->assertSee('Oppo lite');

    }

    /** @test */
    public function it_is_possible_to_see_the_product_details()
    {

        $celulares = Category::factory()->create(["name" => "Celulares y tablets",
            "slug" => "celulares-y-tablets",
            "icon" => 'algo',
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);

        $celulares->subcategories()->create([
            'name' => 'Celulares y smartphones',
            'slug' => Str::slug('Celulares y smartphones'),
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);

        Brand::factory()->create(['id' => '1', 'name' => 'Oppo']);


        $celulares->products()->create($this->withData(['name' => 'Oppo 12']));

        $this->get('products/1')
            ->assertStatus(200)
            ->assertSee('Oppo 12')
            ->assertSee('Marca:')
            ->assertSee('22.99');
    }
}
