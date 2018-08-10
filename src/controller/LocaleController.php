<?php

namespace Tyler36\Localization\Controller;

use App\Http\Controllers\Controller;
use Tyler36\Localization\Localizations;

class LocaleController extends Controller
{
    /**
     * Redirect back with cookie set
     *
     * @param string $locale
     *
     * @return void
     */
    public function session($locale)
    {
        $key = config('localization.session_key', 'locale');

        if (Localizations::isValid($locale)) {
            session([$key => $locale]);
        }

        return redirect()->back();
    }
}
