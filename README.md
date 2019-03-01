PhpClass
========

PhpClass is a PHP class loader that allows you to load and instantiate class files on-the-fly. It was created to be able to load classes which names cannot be known before runtime, as [ReflectionClass](http://php.net/manual/en/class.reflectionclass.php) do not support loading from files.

Installing
----------

```
composer require pyrsmk/php-class
```

Use
---

```php
$phpClass = new PhpClass('path/to/a/file.php');
$myObject = $phpClass->instantiate();
```

If needed, you can retrieve the namespace and the class name from the file without instantiating it :

```php
$phpClass = new PhpClass('path/to/a/file.php');
echo $phpClass->namespace();
echo $phpClass->classname();
```

License
-------

Released under [MIT license](http://dreamysource.mit-license.org).
