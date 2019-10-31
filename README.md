# PSR-16 to PSR-6 Bridge

This is a bridge that converts a PSR-16 cache implementation to PSR-6.

### Install

```bash
composer require topthink/cache-bridge
```

### Use

You need an existing PSR-16 cache object as a constructor argument to the bridge. 

```php
$simpleCache = new SimpleCache();
$psr6pool = new CacheBridge($simpleCache);
```
