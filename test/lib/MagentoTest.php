<?php
class MagentoTest extends PHPUnit_Framework_TestCase
{
// 	protected $runCode = 'default';

// 	protected $runType = 'store';
	
	public function setUp()
	{
		$this->setConfig('bonusbox/general/debug_email', 'no-reply@localhost');
		
		// 		Mage::app($this->runCode, $this->runType);
// 		Zend_Session::$_unitTestEnabled = true;
	}
	

	public static function stubClass($type, $module, $fromClass, $toClass)
	{
		$toClassFile = str_replace('_', '/', str_replace('Stub_' . ucfirst($type) . '_', '', $toClass));
		require_once Mage::getBaseDir() . '/test/stub/' . ucfirst($type) .'/' . ucfirst($toClassFile) . '.php';
		Mage::getConfig()->setNode(strtolower('global/' . $type. 's/' . $module .'/rewrite/' . $fromClass), $toClass);
		if (!(Mage::getModel($module . '/' . $fromClass) instanceof $toClass))
		{
			throw new Exception("Cannot stub class $fromClass to $toClass");
		}
	}
	
	/**
	* sets config value and return array with key and old value for backup
	* @param string $path
	* @param mixed $value
	* @return array
	*/
	public function setConfig($path, $value)
	{
		$store = Mage::app()->getStore();
		$oldValue = $store->getConfig($path);
		$store->setConfig($path, $value);
		return array($path, $oldValue);
	}
	
	/**
	 * Restore config value
	 * @param array $backup
	 */
	public function restoreConfig($backup)
	{
		list($path, $value) = $backup;
		Mage::app()->getStore()->setConfig($path, $value);
	}
	
	
	/**
	* @return Mage_Sales_Model_Quote
	*/
	public function getQuote()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->clear();
		$quote = $session->getQuote();
		$quote->setTotalsCollectedFlag(true);
		$quote->addProduct(Mage::getModel('catalog/product')->load(16));
		$quote->assignCustomer(Mage::getModel('customer/customer')->load(1));
		$quote->getShippingAddress()
			->setFirstname('Max')
			->setLastname('Musterman')
			->setStreet(array('Straße 1'))
			->setCity('City')
			->setPostCode('12345')
			->setCountryId('DE')
			->setTelephone(12345678)
			->setCollectShippingRates(true)
			->setShippingMethod('flatrate_flatrate')
		;
		$payment = $quote->getPayment()->importData(array('method' => 'checkmo'));
		$quote->setTotalsCollectedFlag(false);
		return $quote;
	}
	
	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @return Mage_Sales_Model_Order
	 */
	public function placeOrder($quote = null)
	{
		if (!$quote)
		{
			$quote = $this->getQuote();
		}
		$quote
			->collectTotals()
			->save() // neccessary to set certain fields (e.g. store_to_base_rate)
		;
		$service = Mage::getModel('sales/service_quote', $quote);
		if (method_exists($service, 'submitAll'))
		{
			$service->submitAll();
			return $service->getOrder();
		}
		else {
			return $service->submit();
		}
	}	
	
	
	public function applyCoupon(Mage_Sales_Model_Quote $quote, $couponCode)
	{
		$quote->getShippingAddress()->setCollectShippingRates(true);
		$quote
			->setCouponCode($couponCode)
			->setTotalsCollectedFlag(false)
			->collectTotals()
			->save()
		;
	}
}