<?php

return [

    // This is the default app language
    'default' => app()->getLocale(),

    // This array should contain available languages
    'valid' => [
        app()->getLocale()
    ],

    // The query string used by QueryStringLocaleMiddleware
    'query_string' => 'lang',

    // The header to use By HeaderLocaleMiddleware
    'header' => 'X-localization',
];
