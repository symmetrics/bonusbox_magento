<?php
$path = dirname(dirname($_SERVER['PHP_SELF']));
require_once $path . '/app/Mage.php';
require_once $path . '/test/lib/MagentoTest.php';
Mage::app('default');