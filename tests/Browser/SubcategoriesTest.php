<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestHelpers;

class SubcategoriesTest extends DuskTestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;
    use TestHelpers;

    /** @test */
    public function it_shows_the_subcategories_linked_to_a_category()
    {
        $category1 = Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);

        $category2 = Category::factory()->create(['name' => 'TV, audio y video',
            'slug' => Str::slug('TV, audio y video'),
            'icon' => '<i class="fas fa-tv"></i>']);

        $subcategory1 = Subcategory::create(['category_id' => 1,
            'name' => 'Smartwatches',
            'slug' => Str::slug('Smartwatches'),
            ]);

        $subcategory2 = Subcategory::create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $hiddenSubcategory1 = Subcategory::create(['category_id' => 2,
            'name' => 'Auriculares',
            'slug' => Str::slug('Auriculares'),
        ]);

        $hiddenSubcategory2 = Subcategory::create(['category_id' => 2,
            'name' => 'Altavoces',
            'slug' => Str::slug('Altavoces'),
        ]);

        $this->browse(function (Browser $browser) use ($category1,$category2, $subcategory1, $subcategory2,$hiddenSubcategory1,$hiddenSubcategory2){
            $browser->visit('/')
                ->pause(100)
                ->assertSee('Categorías')
                ->pause(100)
                ->click('@categorias')
                ->pause(100)
                ->assertSee($category1->name)
                ->pause(100)
                ->mouseover('ul.bg-white > li:nth-of-type(1) > a')
                ->pause(100)
                ->assertSee('Subcategorías')
                ->pause(100)
                ->assertSee($subcategory1->name)
                ->pause(100)
                ->assertSee($subcategory2->name)
                ->pause(100)
                ->assertSee($category2->name)
                ->pause(100)
                ->assertDontSee($hiddenSubcategory1->name)
                ->pause(100)
                ->assertDontSee($hiddenSubcategory2->name)
                ->screenshot('subcategory-test');
        });
    }
}
