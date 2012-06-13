<?php
class Bonusbox_Bonusbox_Model_Sales_Observer  
{
	/**
	 * Forwards order to bonuxbox and saves url for success page iframe in session.
	 * Deletes bonusbox coupon code.
	 * @param Varien_Event_Observer $observer
	 */
	public function forwardOrder(Varien_Event_Observer $observer)
	{
		$order = $observer->getOrder();
		if (Mage::helper('bonusbox')->isEnabled())
		{
			if (Mage::helper('bonusbox/successpage')->isOperational())
			{
				// send order to bonusbox
    			$response = Mage::getModel('bonusbox/client_successpages')->post($order);
				Mage::helper('bonusbox')->getSession()->setSuccessPage($response['success_page']);
				
				// delete coupon code from bonusbox
				if (Mage::helper('bonusbox')->isValidBonusboxCouponCode($order->getCouponCode()))
				{
					Mage::getModel('bonusbox/client_coupons')->delete($order->getCouponCode());
				}
				
				// invalidate Customer Badge Cache
  				Mage::helper('bonusbox')->getSession()->setCustomerBadgesByCoupon(null);
			}	
			else {
				Mage::log('Bonusbox Success Page is missing config data.');
			}
		}
	}
	
}