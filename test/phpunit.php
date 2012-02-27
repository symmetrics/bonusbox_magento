<?php 
error_reporting(E_ALL | E_STRICT);

ob_start();
if (isset($_SERVER['LD_LIBRARY_PATH']))
{
	set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['LD_LIBRARY_PATH'] . '\PEAR');
} 
require_once 'PHPUnit/Autoload.php';

$args = $_SERVER['argv'];
array_shift($args); // remove filename
$command = new PHPUnit_TextUI_Command;
$command->run($args, false);