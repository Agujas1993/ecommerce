<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\City;
use App\Models\Department;
use App\Models\District;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class OrderTest extends DuskTestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function only_a_logged_user_can_create_an_order()
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
                ->click('a.bg-red-600')
                ->pause(100)
                ->assertPathIs('/login')
                ->pause(100)
                ->assertSee("Correo electrónico")
                ->assertSee('Contraseña')
                ->screenshot('createOrderloggedUser-test');
        });
    }

    /** @test */
    public function it_shows_the_hidden_form_when_shipping_type_is_2()
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

        $this->createUser();

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
                ->click('a.bg-red-600')
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(1000)
                ->click('div.order-2 > div:nth-of-type(2) > div > label > input')
                ->assertPresent('div.hidden')
                ->assertSee('Departamento')
                ->assertSee('Ciudad')
                ->assertSee('Distrito')
                ->assertSee('Dirección')
                ->assertSee('Referencia')
                ->screenshot('showsHiddenForm-test');
        });
    }

    /** @test */
    public function it_creates_the_order_and_destroys_shopping_cart()
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

        $this->createUser();

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
                ->click('a.bg-red-600')
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(1000)
                ->type('div.bg-white > div.mb-4 > input', 'samuel')
                ->pause(100)
                ->type('div.bg-white > div:nth-of-type(2) > input', '45345453454')
                ->pause(100)
                ->press('CONTINUAR CON LA COMPRA')
                ->pause(1000)
                ->assertPathIs('/orders/1/payment')
                ->click('div.relative > div > span')
                ->pause(100)
                ->assertSeeIn('li.py-6', 'No tiene agregado ningún item en el carrito')
                ->screenshot('createsOrder-test');
        });
    }

    /** @test */
    public function the_selects_load_correctly()
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

        $this->createUser();

        $department = Department::factory()->create(['name' => 'Murcia']);
        $city = City::factory()->create(['name' => 'Alhama', 'cost' => 10, 'department_id' => $department->id]);
        $district = District::factory()->create(['name' => 'Barrio Perdido', 'city_id' => $city->id]);

        $this->browse(function (Browser $browser) use ($product, $department, $city, $district) {
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
                ->click('a.bg-red-600')
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(1000)
                ->click('div.order-2 > div:nth-of-type(2) > div > label > input')
                ->pause(1000)
                ->click('select.form-control')
                ->pause(100)
                ->click('option:nth-of-type(2)')
                ->pause(100)
                ->assertSee($department->name)
                ->pause(100)
                ->click('div.px-6 > div:nth-of-type(3) > select.form-control')
                ->pause(100)
                ->click('div.px-6 > div:nth-of-type(3) > select.form-control > option:nth-of-type(2)')
                ->pause(100)
                ->assertSee($city->name)
                ->pause(100)
                ->click('div.px-6 > div:nth-of-type(4) > select.form-control')
                ->pause(100)
                ->click('div.px-6 > div:nth-of-type(4) > select.form-control > option:nth-of-type(2)')
                ->assertSee($district->name)
                ->screenshot('selectsLoadCorrectly-test');
        });
    }
}
