<?php
class Model_SessionTest extends MagentoTest
{
	/**
	 * 
	 * @return Bonusbox_Bonusbox_Model_Session
	 */
	public function getSession()
	{
		return Mage::getModel('bonusbox/session');
	}
	
	public function testSetCustomerBadgeByCoupon()
	{
		$session = $this->getSession();
		$badge = 2;
		$coupon = 'xyz';
		$session->setCustomerBadgeByCoupon($coupon, $badge);
		$this->assertEquals($badge, $session->getCustomerBadgeByCoupon($coupon));
	}
	

	public function testGetCustomerBadgeByCoupon()
	{
		$session = $this->getSession();
		$session->setCustomerBadgeByCoupon('xyz', 1);
		$session->getCustomerBadgeByCoupon('?'); // no error on non existing coupon
	}
}