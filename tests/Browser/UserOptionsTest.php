<?php

namespace Tests\Browser;


use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestHelpers;

class UserOptionsTest extends DuskTestCase
{

     use RefreshDatabase;
     use DatabaseMigrations;
     use TestHelpers;

    /** @test */
    public function it_shows_the_login_and_register_options()
    {

        $this->createCategory();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->pause(100)
                ->click('.fa-user-circle')
                ->pause(100)
                    ->assertSeeIn('.rounded-md .ring-1','Login')
                ->pause(100)
                    ->assertSeeIn('.rounded-md .ring-1','Registro')
                    ->screenshot('not-logged-test');
        });
    }

    /** @test */
    public function it_shows_the_logout_and_profile_options()
    {
        $this->createCategory();
        $this->createUser();

        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                ->pause(100)
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(100)
                ->click('.rounded-full .object-cover')
                ->pause(100)
                ->assertSeeIn('.rounded-md .ring-1','Perfil')
                ->pause(100)
                ->assertSeeIn('.rounded-md .ring-1','Logout')
                ->screenshot('logged-test');
        });
    }

    /** @test */
    public function the_unauthenticated_user_cant_access_to_authenticate_necessary_routes()
    {

        $this->createUser();
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $order = new Order();
        $order->user_id = '1';
        $order->contact = 'Samuel';
        $order->phone = '42343423234';
        $order->envio_type = '2';
        $order->shipping_cost = 0;
        $order->total = '40';

        $order->content = $product;
        $order->save();

        $this->browse(function (Browser $browser){
            $browser->visit('/orders')
                ->type('email', 'samuel@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(100)
                ->assertPathIs('/orders')
                ->screenshot('needAuthentication-test');
        });
    }

    /** @test */
    public function an_authenticated_user_cant_access_other_authenticated_user_orders()
    {
        $this->createUser();
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        User::factory()->create([
            'name' => 'Pepe',
            'email' => 'pepe@test.com',
            'password' => bcrypt('123'),
        ]);

        $order = new Order();
        $order->user_id = '1';
        $order->contact = 'Samuel';
        $order->phone = '42343423234';
        $order->envio_type = '2';
        $order->shipping_cost = 0;
        $order->total = '40';

        $order->content = $product;
        $order->save();

        $this->browse(function (Browser $browser){
            $browser->visit('/orders/1')
                ->type('email', 'pepe@test.com')
                ->pause(100)
                ->type('password', '123')
                ->pause(100)
                ->press('INICIAR SESIÓN')
                ->pause(100)
                ->assertPathIs('/orders/1')
                ->assertTitle('Prohibido')
                ->assertSeeIn('div.px-4', '403')
                ->assertSeeIn('div.ml-4','ESTA ACCIÓN NO ESTÁ AUTORIZADA')
                ->screenshot('needAuthentication-test');
        });
    }

}
