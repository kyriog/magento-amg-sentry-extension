# magento-amg-sentry-extension

The `magento-amg-sentry-extension` is a Magento 1.5 extension for Sentry
interface.

This plugin is based on Raven client library
[raven-php](https://github.com/getsentry/raven-php).

## Requirements

* PHP >= 5.2
* Magento >= 1.5
* Sentry instance

## Installation (via zip archive)

[Download](../../archive/master.zip) and extract zip archive.

Copy the content of `src` folder into your Magento project folder.

## Installation (via modman)

``` bash
modman init modman clone
git@github.com:wearefarm/magento-sentry-extension.git
```

## Installation: enabling Magento Exception handling

Because Magento catches all exceptions via a global try/catch construct
`set_exception_handler` doesn't work (the exception handler is never reached).
With a small addition to `app/Mage.php` this can be fixed:

``` php
  public static function run($code = '', $type = 'store', $options = array())
  {
    ......
    } catch (Exception $e) {
    if (self::isInstalled() || self::$_isDownloader) {
    //add this line
    self::dispatchEvent('mage_run_exception',array('exception' => $e));
    //-----------------------------------------------------------------
    self::printException($e);
    exit();
    }
  }
```

I hope Magento will include this patch in Magento. Another solution could be
[PHP's runkit](http://www.php.net/manual/en/book.runkit.php) or editing
`errors/report.php`.

## Configuration

In your Magento back-office, go to System → Configuration → Advanced → Developer
→ AMG Sentry.
