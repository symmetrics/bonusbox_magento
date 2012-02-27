<?php
class Model_SalesObserverTest extends MagentoTest
{
	public function testPlaceOrder()
	{
		Mage::helper('bonusbox')->getSession()->setSuccessPage(null);
		$order = $this->placeOrder();
  		$successPage = Mage::helper('bonusbox')->getSession()->getSuccessPage();
 		$this->assertArrayHasKey('url', $successPage);
 		$this->assertArrayHasKey('token', $successPage);
 		$this->assertEmpty(Mage::helper('bonusbox')->getSession()->getCustomerBadgesByCoupon(), 'getCustomerBadgesByCoupon');
	}
	

	public function testPlaceOrderBonusboxDisabled()
	{
		Mage::helper('bonusbox')->getSession()->setSuccessPage(null);
		$backup = $this->setConfig('bonusbox/general/enabled', 0);
		$order = $this->placeOrder();
		$this->restoreConfig($backup);
		
		$this->assertEmpty($order->getCouponCode(), 'getCouponCode');
		$this->assertEmpty(Mage::helper('bonusbox')->getSession()->getSuccessPage(), 'getSuccessPage');
	}
	
	
	public function testControllerSaveOrder()
	{
		Mage::helper('bonusbox')->getSession()->setSuccessPage(null);
		require_once 'Mage/Checkout/controllers/OnepageController.php';
		$controller = new Mage_Checkout_OnepageController(Mage::app()->getFrontController()->getRequest(), Mage::app()->getFrontController()->getResponse());
		$order = $this->placeOrder();
		$controller->getOnepage()->saveOrder();
  		$successPage = Mage::helper('bonusbox')->getSession()->getSuccessPage();
 		$this->assertArrayHasKey('url', $successPage);
 		$this->assertArrayHasKey('token', $successPage);
	}
	
	/**
	 * Ensure that code is saved to order
	 */
	public function testSaveOrderCoupon()
	{
		$this->applyCoupon($quote = $this->getQuote(), $code = Stub_Model_Client_Coupons::SILVER);
		$order = $this->placeOrder($quote);
		$this->assertEquals($code, $order->getCouponCode());
	}


	/**
	*/
	public function testSaveOrderWithoutCoupon()
	{
		$order = $this->placeOrder();
		$this->assertEmpty($order->getCouponCode());
	}
}