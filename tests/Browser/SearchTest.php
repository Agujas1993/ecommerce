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
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/')
                ->type('input', $product->name)
                ->pause(1000)
                ->assertSeeIn('div.px-4',$product->name)
                ->screenshot('searchByName-test');
        });
    }

    /** @test */
    public function it_searchs_by_product_name_in_admin_zone()
    {
        $product = $this->createProduct();
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
                ->type('input.border-gray-300', $product->name)
                ->pause(1000)
                ->assertSeeIn('tbody',$product->name)
                ->screenshot('searchByNameAdmin-test');
        });
    }
}
