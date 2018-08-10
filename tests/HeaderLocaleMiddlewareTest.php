<?php

namespace Tests\Unit;

use Route;
use Tests\TestCase;
use Tyler36\Localization\Middleware\HeaderLocale;

/**
 * Class HeaderLocaleMiddlewareTest
 *
 * @test
 * @group middleware
 * @group api
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class HeaderLocaleMiddlewareTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        //  Register route with middleware
        $this->route = 'api/test';

        Route::get($this->route, function () {
            return 'pass through';
        })->middleware(HeaderLocale::class);

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
    public function it_sets_the_locale_via_request_header()
    {
        // SETUP:      Sanity check
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($this->newLocale, app()->getLocale());

        // VISIT:       With header set
        $this->withHeaders([
                'X-localization' => $this->newLocale,
            ])
            ->get($this->route);

        // ASSERT:      Locale was updated
        $this->assertSame($this->newLocale, app()->getLocale());
    }

    /**
     * @test
     */
    public function it_sets_the_locale_via_configured_request_header()
    {
        // SETUP:      Sanity check
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($this->newLocale, app()->getLocale());

        // Set query string
        $newHeader = 'Accept-language';
        config()->set('localization.header', $newHeader);

        // VISIT:       With header set
        $this->withHeaders([
            $newHeader => $this->newLocale,
            ])
            ->get($this->route);

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

        // VISIT:       Without header set
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

        // VISIT:       With header set
        $this->withHeaders([
                'X-localization' => $invalid,
            ])
            ->get($this->route);

        // ASSERT:      Locale was NOT updated
        $this->assertSame($this->defaultLocale, app()->getLocale());
        $this->assertNotSame($invalid, app()->getLocale());
    }
}
