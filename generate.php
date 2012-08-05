<?php

function mkdirs($dir)
{
	if (!is_dir($dir)) {
		mkdirs(dirname($dir));
		mkdir($dir);
	}
}

$strAutoloadArray = '';
$strClasses = '';

$resMapped = fopen('mapped.php', 'wb');
fwrite($resMapped, '<?php

$map = array(');

$resBenchmark = fopen('benchmark.php', 'wb');
fwrite($resBenchmark, "<?php

\$start = microtime(true);
");

$resFexists = fopen('fexists.php', 'wb');
fwrite($resFexists, '<?php

$start = microtime(true);

');

$resFopen = fopen('fopen.php', 'wb');
fwrite($resFopen, '<?php

$start = microtime(true);

');

$n = 100000;
for ($i=1; $i<=$n; $i++)
{
	$dir = __DIR__ . sprintf('/classes/Vendor%04d/Namespace%04d', $i, $i);
	$class = $dir . sprintf('/Class%04d.php', $i);
	$name = sprintf('Vendor%04d\Namespace%04d\Class%04d', $i, $i, $i);

	mkdirs($dir);

	if (!file_exists($class))
	{
		file_put_contents($class, sprintf('<?php

namespace Vendor%04d\\Namespace%04d;

class Class%04d
{
}
', $i, $i, $i, $i));
	}

	fwrite($resMapped, sprintf('
	%s => %s,', var_export($name, true), var_export($class, true)));

	fwrite($resBenchmark, 'new ' . $name . '();
');

	fwrite($resFexists, sprintf('if (file_exists(%s)) {
	include(%s);
}
', var_export($class, true), var_export($class, true)));

	fwrite($resFopen, sprintf('if (@include(%s)) {
	
}
', var_export($class, true)));

	echo "class $name\n";
}

fwrite($resBenchmark, "
\$stop = microtime(true);
\$time = \$stop - \$start;
\$per = \$time / $n;

printf('execution time: %f seconds
', \$time);
printf('execution per file: %f seconds
', \$per);
");

fwrite($resMapped, "
);

spl_autoload_register(function(\$strClassName) use (\$map) {
	if (isset(\$map[\$strClassName]))
	{
		include(\$map[\$strClassName]);
	}
});

include('benchmark.php');
");

fwrite($resFexists, "
\$stop = microtime(true);
\$time = \$stop - \$start;
\$per = \$time / $n;

printf('execution time: %f seconds
', \$time);
printf('execution per file: %f seconds
', \$per);
");

fwrite($resFopen, "
\$stop = microtime(true);
\$time = \$stop - \$start;
\$per = \$time / $n;

printf('execution time: %f seconds
', \$time);
printf('execution per file: %f seconds
', \$per);
");

fclose($resMapped);
fclose($resBenchmark);
fclose($resFexists);
fclose($resFopen);

