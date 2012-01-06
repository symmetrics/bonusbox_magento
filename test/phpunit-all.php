<?php 
/**
 * @todo move to class
 */
$args = $_SERVER['argv'];
$php = $_SERVER['LD_LIBRARY_PATH'] . '/php.exe';
$phpunit = 'test/phpunit.php';

// init arguments
array_shift($args); // remove filename
foreach ($args as $index => $arg)
{
	if (strpos($arg, 'versions=') === 0)
	{
		$versions = str_replace('versions=', '', $arg);
		$versions = explode(',', $versions);
		unset($args[$index]);
	}
	elseif (strpos($arg, 'versions_basedir=') === 0)
	{
		$basedir = str_replace('versions_basedir=', '', $arg);
		unset($args[$index]);
	}
}

// load all version if not specified
if (!$versions)
{
	$directoryIterator = new DirectoryIterator($basedir);
	foreach ($directoryIterator as $file)
	{
		if ($file->isDir() && file_exists($basedir . '/' . $file->getFilename() . '/' . $phpunit))
		{
			$versions[] = $file->getFilename();
		}
	}
}

// run all phpunit.php for every version
$outputs = array();
echo 'Testing versions: ' . implode(', ', $versions) . "\n\n"; 
$successfulTotal = true;
foreach ($versions as $version)
{
	echo $version . ': ';
	$file = $basedir . '/' . $version . '/' . $phpunit . ' ' . implode(' ', $args);
 	
	$output = exec($php . ' ' . $file);

	$matches = array();
	if ($successful = (bool)preg_match_all('~\((.*)\)~', $output, $matches))
	{
		$results = $matches[1][0];
	}
	else {
		$results = str_replace('.', '', $output);
	}
	$results = explode(',', strtolower($results));
	
	foreach ($results as $result)
	{
		if ($successful)
		{
			list($amount, $type) = explode(' ', trim($result));
		}
		else {
			list($type, $amount) = explode(': ', trim($result));
		}
		if ($type[strlen($type) - 1] != 's')
		{
			$type .= 's';
		}
		$resultDetails[$type] += $amount;
	}
	
	if (!$successful)
	{
		$successfulTotal = false;
	}
	echo $output . "\n";
}

// output summary
foreach ($resultDetails as $type => $amount)
{
	$summary[] = "$amount $type";  
}

echo "\nSummary: " . implode(', ', $summary);