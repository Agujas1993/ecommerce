<?php

namespace Tests\Feature;

use App\Http\Livewire\CartMovil;
use App\Http\Livewire\DropdownCart;
use App\Http\Livewire\Navigation;
use App\Http\Livewire\Search;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class NavTest extends TestCase
{

    use RefreshDatabase;
    use DatabaseMigrations;

    /** @test */
    public function it_loads_the_nav_bar()
    {
        Category::factory()->create(["name" => "Celulares y tablets",
        "slug" => "celulares-y-tablets",
        "icon" => 'algo',
        "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);
        $this->get('/')
            ->assertSeeLivewire('search')
            ->assertSeeLivewire('dropdown-cart')
            ->assertSeeLivewire('navigation');
        Livewire::test(Navigation::class)->assertStatus(200)
        ->assertSee('Categorías')
        ->assertViewHas('categories');
    }

    /** @test */
    public function it_lists_the_categories()
    {
        Category::factory()->create(["name" => "Celulares y tablets",
            "slug" => "celulares-y-tablets",
            "icon" => 'algo',
            "image" => "categories/84b8093bb4bc5ec8f29c8edc374caf22.png",
        ]);
        Category::factory()->create(["name" => "TV, audio y video",
            "slug" => "tv-audio-y-video",
            "icon" => "alga",
            "image" => "categories/7ad845f226a653a4fe7d08f213d826cf.png",
        ]);
        Category::factory()->create(["name" => "Consola y videojuegos",
            "slug" => "consola-y-videojuegos",
            "icon" => "algas",
            "image" => "categories/7ad845f226a653a4fe7d08f213d826cf.png",
        ]);
        Livewire::test(Navigation::class)
            ->assertStatus(200)
            ->assertSee('Celulares y tablets')
            ->assertSee('TV, audio y video')
            ->assertSee('Consola y videojuegos');
    }
    /** @test */
    public function it_loads_the_search_input()
    {
        Livewire::test(Search::class)
            ->assertStatus(200)
            ->assertSee('¿Estás buscando algún producto?');
    }

    /** @test */
    public function it_loads_the_dropdown_cart()
    {
        Livewire::test(DropdownCart::class)
            ->assertStatus(200)
            ->assertSee('No tiene agregado ningún item en el carrito');
    }

    /** @test */
    public function it_loads_the_dropdown_cart_responsive()
    {
        Livewire::test(CartMovil::class)
            ->assertStatus(200)
            ->assertSee('Carrito de compras');
    }
}
