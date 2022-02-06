<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Spatie\Permission\Models\Role;

class SearchTest extends DuskTestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function it_searchs_by_product_name()
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
            'name' => 'Aspirador',
            'slug' => Str::slug('Aspirador'),
            'description' => 'Aspirador' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->type('input', 'Aspirador')
                ->pause(1000)
                ->assertSeeIn('div.px-4',$product->name)
                ->screenshot('searchByName-test');
        });
    }

    /** @test */
    public function it_searchs_by_product_name_in_admin_zone()
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
            'name' => 'Aspirador',
            'slug' => Str::slug('Aspirador'),
            'description' => 'Aspirador' . 'moderno año 2022',
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);

        $product->images()->create(['url' => 'storage/324234324323423.png']);

        Role::create(['name' => 'admin']);

        User::factory()->create([
            'name' => 'Samuel Garcia',
            'email' => 'samuel@test.com',
            'password' => bcrypt('123'),
        ])->assignRole('admin');


        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/admin')
                ->pause(100)
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(1000)
                ->type('input.border-gray-300', 'Aspirador')
                ->pause(1000)
                ->assertSeeIn('tbody',$product->name)
                ->screenshot('searchByNameAdmin-test');
        });
    }
}
