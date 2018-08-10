# Introduction
This package is designed to simplify multi-locale applications.
Simply add the required middleware and update your configuration.

## Installation
- Install package
```
composer require tyler36/localization
```

- Publish configuration
Run the command below to publish the configuration file
```
php artisan vendor:publish --provider=tyler36/localization
```

- Add supported languages
By default, only the current app locale is deemed valid.
You can add additional languages to configuration file (```config/localization.php```). Can't see the file? Perhaps you forgot to publish it; see the above step. For a list of internationally recognized language codes, see https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes.

- Apply middleware
Add the desired middle to the ```app/Http/Kernel.php``` file in the required location. For more details, see the Laravel documentation (https://laravel.com/docs/master/middleware#registering-middleware)


## Middleware

### Option 1: Header request
This middleware uses a request header to set the locale. By default, the query string is set to ```X-localization``` but can be changed through the config file. It's ideal for making request via javascript.

- Register
```\Tyler36\Localization\Middleware\HeaderLocale::class```


- Set the header in via vanilla javascript or preferred javascript framework. For example, you can apply the header to all ```Axios``` request with the following:
```
// Set language to German ('de')
window.axios = axios;
window.axios.defaults.headers.common['X-localization'] = 'de';
```
Now, when you send a request to the endpoint Laravel will update the locale to match the setting, assuming it is configured as 'valid'.


### Option 2: Query String
This middleware is designed to use a query string.

- Register
```\Tyler36\Localization\Middleware\QueryStringLocale::class```

- Add the query string to a URL.
By default, the query string is set to ```lang``` but can be changed through the config file.
```
    // This will return the site with default locale
    https://cool-site.dev

    // This will return the site with locale to to German (de)
    https://cool-site.dev?lang=de
```


### Option 3: Member preference
This middleware uses an attribute on the User model to set the locale. When a member is logged in, it check their saved locale and applies it.

- Register
```\Tyler36\Localization\Middleware\MemberLocale::class```

- Create the attribute on User models
By default, the query string is set to ```locale``` but can be changed through the config file.
```
    $table->string('locale', 2)->default(app()->getLocale());
```
