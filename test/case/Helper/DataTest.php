<?php
class Helper_DataTest extends MagentoTest
{
	public function _testGetCustomerBadge()
	{
		$request = new Varien_Object();
		$request->setCouponCode('Winter');
		$badge = Mage::helper('bonusbox')->getCustomerBadge($request);
		$this->assertEquals(23, $badge);
	}
	
	
	public function testGetBadgeOptions()
	{
		$options = Mage::helper('bonusbox')->getBadgeOptions();
		$this->assertGreaterThan(0, count($options));
		foreach ($options as $option)
		{
			$this->assertNotEmpty($option['value']);
			$this->assertNotEmpty($option['label']);
		}
	}
	
	
	public function testHandleErrorLive()
	{
		$old = $this->setConfig('bonusbox/general/live', 1);
		Mage::helper('bonusbox')->handleError('Error');
		$this->setConfig('bonusbox/general/live', $old);
	}
	
	/**
	 * @expectedException Bonusbox_Bonusbox_Exception
	 */
	public function testHandleErrorTest()
	{
		$old = $this->setConfig('bonusbox/general/live', 0);
		Mage::helper('bonusbox')->handleError('Error');
		$this->setConfig('bonusbox/general/live', $old);
	}
	
	
	public function setConfig($path, $value)
	{
		$store = Mage::app()->getStore();
		$oldValue = $store->getConfig($path);
		$store->setConfig($path, $value);
		return $oldValue;
	}
}