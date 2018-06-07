<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tyler36\Localization\Localizations;

/**
 * Class LocalizationsTest
 *
 * @test
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class LocalizationsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_current_locale()
    {
        $current = app()->getLocale();
        $this->assertSame(app()->getLocale(), Localizations::current());
    }

    /**
     * @test
     */
    public function it_can_set_locale()
    {
        $current   = app()->getLocale();
        $newLocale = 'ja';

        // Sanity check
        $this->assertSame($current, Localizations::current());
        $this->assertNotSame($current, $newLocale);

        // Apply
        Localizations::set($newLocale);

        // ASSERT
        $this->assertSame($newLocale, Localizations::current());
    }

    /**
     * @test
     * @dataProvider localeProvider
     *
     * @param mixed $locale
     * @param bool  $expectedResult
     */
    public function it_can_check_if_locale_is_valid($locale, $expectedResult)
    {
        $this->assertSame($expectedResult, Localizations::isValid($locale));
    }

    /**
     * @return array
     */
    public function localeProvider()
    {
        return [
            'default' => ['en', true],
            'empty'   => ['', false],
            'null'    => [null, false],
            'invalid' => [1, false],
        ];
    }

    /**
     * @test
     */
    public function valid_locales_can_be_set_through_config()
    {
        $valid = ['en', 'ja'];
        config()->set('localizations.valid', $valid);

        // ASSERT:      Language exists
        $this->assertTrue(Localizations::isValid('en'));
        $this->assertTrue(Localizations::isValid('ja'));

        // ASSERT:      Language does NOT exist
        $this->assertFalse(Localizations::isValid('aa'));
        $this->assertFalse(Localizations::isValid('zz'));
    }
}
