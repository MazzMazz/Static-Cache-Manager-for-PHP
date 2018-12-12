# Static Cache Manager for PHP
## About

In some applications it is very useful to initialize a set of data and store it in a static variable to be availbale applicationwide without having to initialize it everytime it is needed. 
Having a lot of such static cache variables can lead to bad code quality.

The `Global-Static-Cache-Manager` introduces a central manager to handle multiple static caches.   

The StaticCacheContainer uses the [PSR-11 Interface](https://www.php-fig.org/psr/psr-11/)   
The StaticCache uses the [PSR-16 Interface](https://www.php-fig.org/psr/psr-16/)

## Install

Just add the composer package

`composer require mazz/php-global-static-cache-manager`

[What is composer?](https://getcomposer.org/)    

## Usage

Get instance of the global `StaticCacheContainer` and get the Cache with Key `currency_cache`. If no cache with this key is available a new `StaticCache` for the given key is created and returned.

```php
// Receive the Cache with the currency_cache
$currencyCache = StaticCacheContainer::getInstance()->get('currency_cache');
   
// Fill the cache one by one
$currencyCache->set(1, 'currency1');
$currencyCache->set(2, 'currency2');
$currencyCache->set(3, 'currency3');
    
// It is also possible to fill multiple at once
$currencyCache->setMultiple([
    4 => 'currency4',
    5 => 'currency5',
]);
```

You then can simply access the cache in any part of your application
```php
// Get the currency which is saved on index 4
$currency = StaticCacheContainer::getInstance()->get('currency_cache')->get(4);
   
// it is also possible to set a default value
$currency = StaticCacheContainer::getInstance()->get('currency_cache')->get(42, 'defaultCurrency');
   
// or load multiple keys at once
$currency = StaticCacheContainer::getInstance()->get('currency_cache')->get([1,2,3);
```

You may also check if the cache is already initialized. Usefull if you want to fill the cache only once.

```php

// Returns true if cache with the key my_cache_key exists, false otherwise
if(!StaticCacheContainer::getInstance()->has('my_cache_key')) {
    // you may want to fill the cache here
}

```

The `StaticCache` introduces also other useful functions: `has($key)`, `clear()`, `delete($key)`

### License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
