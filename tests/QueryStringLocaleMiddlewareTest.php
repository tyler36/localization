<?php

namespace Tests\Unit;

use Route;
use Tests\TestCase;
use Tyler36\Localization\QueryStringLocaleMiddleware;

/**
 * Class QueryStringLocaleMiddlewareTest
 *
 * @test
 * @group middleware
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class QueryStringLocaleMiddlewareTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        //  Register route with middleware
        $this->route = 'test';
        Route::get($this->route, function () {
            return 'pass through';
        })->middleware(QueryStringLocaleMiddleware::class);

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
    public function it_sets_the_locale_via_query_string()
    {
        // SETUP:      Sanity check
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($this->newLocale, app()->getLocale());

        // VISIT:       With query string set
        $this->get("{$this->route}?lang={$this->newLocale}");

        // ASSERT:      Locale was updated
        $this->assertSame($this->newLocale, app()->getLocale());
    }

    /**
     * @test
     */
    public function it_sets_the_locale_via_configured_query_string()
    {
        // SETUP:      Sanity check
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($this->newLocale, app()->getLocale());

        // Set query string
        $queryStringName = 'locale';
        config()->set('localization.query_string', $queryStringName);

        // VISIT:       With query string set
        $this->get("{$this->route}?{$queryStringName}={$this->newLocale}");

        // ASSERT:      Locale was updated
        $this->assertSame($this->newLocale, app()->getLocale());
    }

    /**
     * @test
     */
    public function it_does_nothing_if_header_is_not_set()
    {
        // SETUP:      Sanity check
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($this->newLocale, app()->getLocale());

        // VISIT:       Without query string set
        $this->get($this->route);

        // ASSERT:      Locale was NOT updated
        $this->assertSame($this->defaultLocale, app()->getLocale());
    }

    /**
     * @test
     */
    public function it_does_not_set_invalid_locale()
    {
        // SETUP:      Sanity check
        $invalid = 'zz';
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($invalid, app()->getLocale());

        // VISIT:       With query string set
        $this->get("{$this->route}?lang=${invalid}");

        // ASSERT:      Locale was NOT updated
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($invalid, app()->getLocale());
    }
}
