<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Route;
use Tests\TestCase;
use Tyler36\Localization\Localizations;
use Tyler36\Localization\Middleware\MemberLocale;

/**
 * Class MemberLocaleMiddlwareTest
 *
 * @test
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class MemberLocaleMiddlwareTest extends TestCase
{
    use DatabaseMigrations;

    protected $localeColumn;

    protected function setUp()
    {
        parent::setUp();

        //  Register route with middleware
        $this->route = 'test';
        Route::get($this->route, function () {
            return 'pass through';
        })->middleware(MemberLocale::class);

        // SETUP:   Known stating state
        app()->setLocale('en');
        $this->defaultLocale = app()->getLocale();
        $this->newLocale     = 'ja';
        $this->assertNotSame($this->newLocale, $this->defaultLocale);

        // SETUP:       Valid locales
        config()->set('localizations.valid', [$this->defaultLocale, $this->newLocale]);

        $this->localeColumn = config('localizations.attribute_name', 'locale');
    }

    /**
     * @test
     */
    public function it_sets_the_locale_to_members_preference()
    {
        $member           = factory(User::class)->create([$this->localeColumn => $this->newLocale]);
        $this->assertNotSame($member->{$this->localeColumn}, $this->defaultLocale);

        $this->assertSame(app()->getLocale(), $this->defaultLocale);
        $this->actingAs($member)
            ->get($this->route);

        $this->assertSame(app()->getLocale(), $this->newLocale);
    }

    /**
     * @test
     */
    public function it_only_applies_valid_locales()
    {
        $member           = factory(User::class)->create([$this->localeColumn => 'zz']);
        $this->assertNotSame($member->{$this->localeColumn}, $this->defaultLocale);
        $this->assertFalse(Localizations::isValid($member->{$this->localeColumn}));

        $this->assertSame(app()->getLocale(), $this->defaultLocale);
        $this->actingAs($member)
            ->get($this->route);

        $this->assertSame(app()->getLocale(), $this->defaultLocale);
    }

    /**
     * @test
     */
    public function it_does_nothing_if_guest()
    {
        $this->assertSame(app()->getLocale(), $this->defaultLocale);

        $this->get($this->route);

        $this->assertSame(app()->getLocale(), $this->defaultLocale);
    }
}
