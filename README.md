benchmark file_exists+include vs. @include
==========================================

file_exists+include
-------------------

```php
if (file_exists(file.php)) {
    include(file.php);
}
```

```
$ php -d apc.cache=off fexists.php
execution time: 2.849698 seconds
execution per file: 0.000028 seconds
```

against @include
----------------

```php
if (@include(file.php)) {
    
}
```

```
$ php -d apc.cache=off fopen.php
execution time: 2.595434 seconds
execution per file: 0.000026 seconds
```

results
-------

using @include is 0,254264 seconds faster than file_exists+include for 100.000 files.

using @include is 0,002 miliseconds faster per file than file_exists+include.


benchmark array-mapped against psr-0 loader
===========================================

array-mapped loader
-------------------

```php
spl_autoload_register(function($strClassName) use ($map) {
	if (isset($map[$strClassName]))
	{
		include($map[$strClassName]);
	}
});
```

```
$ php -d apc.cache=off mapped.php
execution time: 3.240352 seconds
execution per file: 0.000032 seconds
```

against psr-0 loader
--------------------

```php
spl_autoload_register(function($strClassName) use ($dir) {
	$file = $dir . '/' . str_replace('\\', '/', $strClassName) . '.php';

	if (file_exists($file))
	{
		include($file);
	}
});
```

```
$ php -d apc.cache=off psr0.php
execution time: 3.724490 seconds
execution per file: 0.000037 seconds
```

results
-------

the array-mapped loader is 0,484138 seconds faster than the psr-0 loader for 100.000.

the array-mapped loader is 0,005 miliseconds faster per file than the psr-0 loader.
