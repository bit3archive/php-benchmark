<?php

$dir = __DIR__ . '/classes';

spl_autoload_register(function($strClassName) use ($dir) {
	$file = $dir . '/' . str_replace('\\', '/', $strClassName) . '.php';

	if (file_exists($file))
	{
		include($file);
	}
});

include('benchmark.php');
