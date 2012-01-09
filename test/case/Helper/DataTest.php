<?php
class Helper_DataTest extends MagentoTest
{
	public function testIsValidBonusboxCouponCode()
	{
		$this->assertTrue(Mage::helper('bonusbox')->isValidBonusboxCouponCode(Stub_Model_Client_Coupons::GOLD));
		$this->assertFalse(Mage::helper('bonusbox')->isValidBonusboxCouponCode('Regular'));
	}
	
	
	public function testGetCustomerBadge()
	{
		$request = new Varien_Object();
		$request->setCouponCode(Stub_Model_Client_Coupons::GOLD);
		$badge = Mage::helper('bonusbox')->getCustomerBadge($request);
		$this->assertEquals(Stub_Model_Client_Badges::GOLD, $badge);
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
		$backup = $this->setConfig('bonusbox/general/live', 1);
		Mage::helper('bonusbox')->handleError('Error');
		$this->restoreConfig($backup);
	}
	
	/**
	 * @expectedException Bonusbox_Bonusbox_Exception
	 */
	public function testHandleErrorTest()
	{
		$backup = $this->setConfig('bonusbox/general/live', 0);
		Mage::helper('bonusbox')->handleError('Error');
		$this->restoreConfig($backup);
	}
}