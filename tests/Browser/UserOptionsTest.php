<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserOptionsTest extends DuskTestCase
{
    /** @test */
    public function it_shows_the_login_and_register_options()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('.fa-user-circle')
                    ->assertSeeIn('.rounded-md .ring-1','Login')
                    ->assertSeeIn('.rounded-md .ring-1','Registro')
                    ->screenshot('not-logged-test');
        });
    }

    /** @test */
    public function it_shows_the_logout_and_profile_options()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'samuel@test.com')
                ->type('password', '123')
                ->press('INICIAR SESIÓN')
                ->click('.rounded-full .object-cover')
                ->assertSeeIn('.rounded-md .ring-1','Logout')
                ->assertSeeIn('.rounded-md .ring-1','Perfil')
                ->screenshot('logged-test');
        });
    }
}
