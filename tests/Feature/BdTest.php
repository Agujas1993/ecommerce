<?php

namespace Tests\Feature;

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
use Tests\TestCase;

class BdTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /** @test */
    public function it_saves_the_shopping_cart_when_user_logs_out()
    {
        $user = User::factory()->create([
            'name' => 'Samuel Garcia',
            'email' => 'samuel@test.com',
            'password' => bcrypt('123'),
        ]);

        $category = Category::factory()->create(["name" => "Celulares y tablets",
            "slug" => "celulares-y-tablets",
            "icon" => 'algo',
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);

        $subcategory = Subcategory::create([
                'category_id' => 1,'name' => 'Celulares y smartphones',
                'slug' => Str::slug('Celulares y smartphones'),
            ]
        );

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
        $user = User::factory()->create([
            'name' => 'Samuel Garcia',
            'email' => 'samuel@test.com',
            'password' => bcrypt('123'),
        ]);

        $category = Category::factory()->create(["name" => "Celulares y tablets",
            "slug" => "celulares-y-tablets",
            "icon" => 'algo',
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);

        $subcategory = Subcategory::create([
                'category_id' => 1,'name' => 'Celulares y smartphones',
                'slug' => Str::slug('Celulares y smartphones'),
            ]
        );

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

        json_decode($order->content);
        foreach (Cart::content() as $item) {
            discount($item);
        }

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => $product->name, 'quantity' => $product->quantity - 2]);


    }
}
