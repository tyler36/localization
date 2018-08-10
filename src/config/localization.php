<?php

return [
    // This array should contain available languages
    'valid' => [
        app()->getLocale(),
    ],

    // The header to use by HeaderLocale middleware
    'header' => 'X-localization',

    // Name of the attribute on user model that contains preferred local used by MemberLocale middleware
    'attribute_name' => 'locale',

    // The query string used by QueryStringLocale middleware
    'query_string' => 'locale',
];
