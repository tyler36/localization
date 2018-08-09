<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Route;
use Tests\TestCase;
use Tyler36\Localization\Localizations;
use Tyler36\Localization\MemberLanguageMiddleware;

/**
 * Class MemberLanguageMiddlwareTest
 *
 * @test
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class MemberLanguageMiddlwareTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        //  Register route with middleware
        $this->route = 'test';
        Route::get($this->route, function () {
            return 'pass through';
        })->middleware(MemberLanguageMiddleware::class);

        // SETUP:   Known stating state
        app()->setLocale('en');
        $this->defaultLocale = app()->getLocale();
        $this->newLocale     = 'ja';
        $this->assertNotSame($this->newLocale, $this->defaultLocale);

        // SETUP:       Valid locales
        config()->set('localizations.valid', [$this->defaultLocale, $this->newLocale]);
    }

    /**
     * @test
     */
    public function it_sets_the_locale_to_members_preference()
    {
        $languageField    = 'lang';
        config()->set('localizations.attribute_name', $languageField);

        $member           = factory(User::class)->create([$languageField => $this->newLocale]);
        $this->assertNotSame($member->{$languageField}, $this->defaultLocale);

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
        $languageField    = 'lang';
        config()->set('localizations.attribute_name', $languageField);

        $member           = factory(User::class)->create([$languageField => 'zz']);
        $this->assertNotSame($member->{$languageField}, $this->defaultLocale);
        $this->assertFalse(Localizations::isValid($member->{$languageField}));

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
