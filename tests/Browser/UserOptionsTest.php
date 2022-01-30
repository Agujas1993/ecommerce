<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserOptionsTest extends DuskTestCase
{

     use RefreshDatabase;
     use DatabaseMigrations;

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
        User::factory()->create([
            'name' => 'Samuel Garcia',
            'email' => 'samuel@test.com',
            'password' => bcrypt('123'),
        ]);

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
}
