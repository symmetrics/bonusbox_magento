<?php
class Bonusbox_Bonusbox_Model_Sales_Observer  
{
	/**
	 * Forward order to bonuxbox and retrieve url for success page iframe.
	 * @param Varien_Event_Observer $observer
	 */
	public function forwardOrder(Varien_Event_Observer $observer)
	{
		$order = $observer->getOrder();
		if (Mage::helper('bonusbox')->isEnabled())
		{
			if (Mage::helper('bonusbox/successpage')->isOperational())
			{
				try {
					$response = Mage::getModel('bonusbox/client_successpages')->post($order);
					Mage::helper('bonusbox')->getSession()
						->setSuccessPage($response['success_page'])
    					->setCustomerBadgesByCoupon(null) // invalidate Customer Badge Cache
    				;
				}
				catch (Exception $ex)
				{
					Mage::helper('bonusbox')->handleError($ex);
				}
			}	
			else {
				Mage::log('Bonusbox Success Page is missing config data.');
			}
		}
	}
}