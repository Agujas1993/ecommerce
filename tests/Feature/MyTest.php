<?php

namespace Tests\Feature;

use App\Http\Livewire\CartMovil;
use App\Http\Livewire\DropdownCart;
use App\Http\Livewire\Navigation;
use App\Http\Livewire\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class MyTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_loads_the_nav_bar()
    {
        self::markTestIncomplete();
        Livewire::test(Navigation::class)
        ->assertSee('Categorías');
    }
    /** @test */
    public function it_loads_the_search_bar()
    {
        Livewire::test(Search::class)
            ->assertSee('¿Estás buscando algún producto?');
    }

    /** @test */
    public function it_loads_the_dropdown_cart()
    {
        Livewire::test(DropdownCart::class)
            ->assertSee('No tiene agregado ningún item en el carrito');
    }

    /** @test */
    public function it_loads_the_dropdown_cart_responsive()
    {
        Livewire::test(CartMovil::class)
            ->assertSee('Carrito de compras');
    }
}
