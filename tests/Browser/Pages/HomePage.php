<?php


namespace Tests\Browser\Pages;

use App\Models\User;
use App\Models\Adoption;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;
use phpDocumentor\Reflection\DocBlock\Description;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class HomePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '.',
        ];
    }

    public function testShowListOfAdoptions(Browser $browser)
    {
        /** @var Collection $eligible */
        $eligible = Adoption::all();
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $elements = $browser->elements('.card');
        $count = count($elements);
        assertCount(4, $elements, "Four cards should be displayed, $count was found!");
        foreach($browser->elements('.card') as $card) {
            $name = trim($card->findElement(WebDriverBy::className('pet-name'))->getText());
            $found = $eligible->firstWhere(fn(Adoption $adoption) => $adoption->name == $name);
            assertEquals($name, $found?->name, "No card contains the name " . $name . " under \".card .pet-name\" (looking at the screenshots may help)");

            $description = trim($card->findElement(WebDriverBy::className('pet-description'))->getText());
            assertEquals($description, $found?->description, "No card contains the description " . $description . " under \".card .pet-description\" (looking at the screenshots may help)");
            $eligible = $eligible->reject(fn(Adoption $adoption) => $adoption->name == $name);
        }
    }

    public function testRegisterUser(Browser $browser)
    {
        $browser->visit('');
        $browser->assertPresent('.register-link');
        $browser->click('.register-link');
        $browser->assertPathIs('/register');
        $browser->assertPresent('input[type=text][name=name]');
        $browser->assertPresent('input[type=email][name=email]');
        $browser->assertPresent('input[type=password][name=password]');
        $browser->assertPresent('input[type=password][name=password-confirmation]');
        $browser->type('input[type=text][name=name]', 'John Doe');
        $browser->type('input[type=email][name=email]', 'john@doe.com');
        $browser->type('input[type=password][name=password]', '1234');
        $browser->type('input[type=password][name=password-confirmation]', '1234');
        $browser->assertPresent('.register-submit');
        $browser->click('.register-submit');
        $browser->assertPathIs('/');
        $browser->assertAuthenticated();
        $browser->logout();
    }

    public function testLoginUser(Browser $browser)
    {
        $browser->visit('/');
        $browser->assertPresent('.login-link');
        $browser->click('.login-link');
        $browser->assertPathIs('/login');
        $browser->assertPresent('input[type=email][name=email]');
        $browser->assertPresent('input[type=password][name=password]');
        $browser->type('input[type=email][name=email]', 'john@doe.com');
        $browser->type('input[type=password][name=password]', '1234');
        $browser->assertPresent('#login-submit');
        $browser->click('#login-submit');
        $browser->assertPathIs('/');
        $browser->assertAuthenticated();
    }

    public function testUserIsLoggedIn(Browser $browser)
    {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertPresent('.user-name');
        $browser->assertSeeIn('.user-name', User::find(1)->name);

    }

    public function testUserLogout(Browser $browser)
    {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertSeeIn('.user-name', User::find(1)->name);
        $browser->assertPresent('.logout-link');
        $browser->click('.logout-link');
        $browser->assertPathIs('/');
        $browser->assertPresent('.register-link');
        $browser->assertPresent('.login-link');
        $browser->assertGuest();
    }

    public function testPerformAdoptOwnPetAndOtherUsersPet(Browser $browser, User $user)
    {
        $browser->loginAs($user);
        $browser->visit('/');
        $others = [];

        foreach($browser->elements('div.card-body') as $element)
        {
            $link = $element->findElement(WebDriverBy::className('pet-show'));
            $others[] = $link->getAttribute('href');
        }
        assertCount(4, $others, 'Should have 4 routes for other users, but ' . count($others) . ' was found instead. Be sure to use the route(..) helper method when creating links to routes');
        $browser->visit($others[1]);
        $browser->assertUrlIs($others[1]);
        $browser->assertSee(Adoption::find(2)->name);
        $browser->assertSee(Adoption::find(2)->description);
        $browser->assertPresent('.pet-adopt');
        $browser->click('.pet-adopt');
        $browser->assertPathIs('/');
        $browser->assertSee("Pet " . Adoption::find(2)->name . " adopted successfully");
    }

    public function showCurrentUserAdoptions(Browser $browser)
    {
        $browser->loginAs(User::find(1));
        $browser->visit('/');
        $browser->assertPresent('.adoption-mine');
        $browser->click('.adoption-mine');
        $browser->assertPathIs('/adoptions/mine');
        $browser->assertSee(Adoption::find(3)->name);
        $browser->assertSee(Adoption::find(4)->name);
        $browser->assertDontSee(Adoption::find(1)->name);
        $browser->assertDontSee(Adoption::find(2)->name);
        $browser->visit('/adoptions/3');
        $browser->assertNotPresent('.pet-adopt');
        $browser->visit('/adoptions/4');
        $browser->assertNotPresent('.pet-adopt');
    }
}


