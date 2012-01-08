<?php
$path = dirname(dirname($_SERVER['PHP_SELF']));
if ($path == '.')
{
	$includePaths = explode(PATH_SEPARATOR, get_include_path());
	$search = '\app\code\local';
	foreach ($includePaths as $path)
	{
		$result = substr($path, 0, - strlen($search));
		if ($result . $search == $path)
		{
			break;
		}
		$result = null;
	}
	if ($result)
	{
		$path = $result;
	}
	else {
		throw new Exception('No base path found!');
	}
}
require_once $path . '/app/Mage.php';
require_once $path . '/test/lib/MagentoTest.php';

Mage::app('default');
session_start();

MagentoTest::stubClass('model', 'bonusbox', 'client_successpages', 'Stub_Model_Client_Successpages');
MagentoTest::stubClass('model', 'bonusbox', 'client_badges', 'Stub_Model_Client_Badges');
MagentoTest::stubClass('model', 'bonusbox', 'client_coupons', 'Stub_Model_Client_Coupons');
MagentoTest::stubClass('model', 'bonusbox', 'client', 'Stub_Model_Client');
MagentoTest::stubClass('model', 'salesrule', 'validator', 'Stub_Model_SalesRule_Validator');

if (!(Mage::helper('bonusbox') instanceof Bonusbox_Bonusbox_Helper_Data))
{
	throw new Exception('Module not installed properly');
} 
