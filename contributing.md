## Security Vulnerabilities

If you discover a security vulnerability within Square Platform, please send an email to Daniel Bănciulea at daniel.banciulea@protonmail.com.

## Pull Requests

All pull requests should be sent to [develop](https://github.com/systeady/square-platform/tree/develop) branch.

## Coding Style

Square Platform follows the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard and the [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) autoloading standard.

### PHPDoc

Below is an example of a valid Square Platform documentation block. Note that the `@param` attribute is followed by two spaces, the argument type, two more spaces, and finally the variable name:

```php
/**
 * Method description.
 *
 * @param  array  $paramOne
 * @param  string|null  $paramTwo
 * @param  bool  $paramThree
 * @return void
 *
 * @throws \Exception
 */
public function methodName($paramOne, $paramTwo = null, $paramThree = false)
{
    //
}
```

### StyleCI

[![StyleCI](https://github.styleci.io/repos/211863399/shield?branch=master)](https://github.styleci.io/repos/211863399)

StyleCI will automatically merge any style fixes into the Square Platform repository after pull requests are merged.
