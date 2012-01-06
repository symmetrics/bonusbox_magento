<?php
class Model_SalesObserverTest extends MagentoTest
{
	public function testPlaceOrder()
	{
 		$order = $this->placeOrder();
  		$successPage = Mage::helper('bonusbox')->getSession()->getSuccessPage();
 		$this->assertArrayHasKey('url', $successPage);
 		$this->assertArrayHasKey('token', $successPage);
	}
	
	
	public function testControllerSaveOrder()
	{
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
}