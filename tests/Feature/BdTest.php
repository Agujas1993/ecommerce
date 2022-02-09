<?php

namespace Tests\Feature;

use App\Http\Livewire\CreateOrder;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\TestHelpers;

class BdTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use TestHelpers;


    /** @test */
    public function it_saves_the_shopping_cart_when_user_logs_out()
    {
        $user = $this->createUser();
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->get('/login')->assertSee('Correo electrónico');
        $credentials = [
            "email" => $user->email,
            "password" => '123'
        ];

        $response = $this->post('/login', $credentials);
        $response->assertRedirect('/dashboard');
        $this->assertCredentials($credentials);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => '2',
            'price' => $product->price,
            'weight' => 550,
        ]);

        Auth::logout();

        $this->assertDatabaseHas('shoppingcart', ['identifier' => $user->id]);

        $this->get('/login')->assertSee('Correo electrónico');

        $this->post('/login', $credentials);

        $this->get('/shopping-cart')->assertSee($product->name);

    }

    /** @test */
    public function the_stock_changes_when_creating_an_order(){

        $user = $this->createUser();
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->get('/login')->assertSee('Correo electrónico');
        $credentials = [
            "email" => $user->email,
            "password" => '123'
        ];

        $response = $this->post('/login', $credentials);
        $response->assertRedirect('/dashboard');
        $this->assertCredentials($credentials);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => '2',
            'price' => $product->price,
            'weight' => 550,
        ]);



        $order = new Order();
        $order->user_id = '1';
        $order->contact = 'Samuel';
        $order->phone = '42343423234';
        $order->envio_type = '2';
        $order->shipping_cost = 0;
        $order->total = '40';
        $order->content = Cart::content();
        $order->status = 2;
        $order->save();

        foreach (Cart::content() as $item) {
            discount($item);
        }


        $this->get('/orders')->assertSee($order->id);


        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => $product->name, 'quantity' => $product->quantity - 2]);


    }

    /** @test */
    public function orders_are_canceled_after_10_minutes(){

        $user = $this->createUser();
        $product = $this->createProduct();
        $product->images()->create(['url' => 'storage/324234324323423.png']);

        $this->get('/login')->assertSee('Correo electrónico');
        $credentials = [
            "email" => $user->email,
            "password" => '123'
        ];

        $response = $this->post('/login', $credentials);
        $response->assertRedirect('/dashboard');
        $this->assertCredentials($credentials);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => '2',
            'price' => $product->price,
            'weight' => 550,
        ]);

        $order = new Order();
        $order->user_id = '1';
        $order->contact = 'Samuel';
        $order->phone = '42343423234';
        $order->envio_type = '1';
        $order->shipping_cost = 0;
        $order->total = '40';
        $order->content = Cart::content();
        $order->status = 1;
        $order->created_at = now()->subMinutes(20);
        $order->updated_at = now()->subMinutes(20);
        $order->save();

        $this->actingAs($user)->get('/orders')
        ->assertOk()->assertSee($order->id);


        $this->assertDatabaseHas('orders', ['id'=>$order->id, 'user_id' => $user->id, 'status'=> '5']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => $product->name, 'quantity' => $product->quantity]);


    }
}
