<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Route;
use Tests\TestCase;
use Tyler36\Localization\Middleware\SessionLocale;

/**
 * Class SessionMiddlewareTest
 *
 * @test
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class SessionMiddlewareTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        //  Register route with middleware
        $this->route = 'test';
        Route::get($this->route, function () {
            return 'pass through';
        })->middleware(SessionLocale::class);

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
    public function it_can_set_locale_via_session()
    {
        $languageField    = 'lang';
        config()->set('localization.session_key', $languageField);

        // Sanity check
        $this->assertSame($this->defaultLocale, app()->getLocale());

        // VISIT
        $response = $this->get($this->route);
        $response->assertSessionMissing($languageField);
        $this->assertSame($this->defaultLocale, app()->getLocale());

        // VISIT:       Route with updated session
        session([$languageField => $this->newLocale]);
        $response = $this->get($this->route);
        $response->assertSessionHas($languageField, $this->newLocale);
        $this->assertSame($this->newLocale, app()->getLocale());
    }
}
