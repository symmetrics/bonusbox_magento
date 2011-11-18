<?php
class Bonusbox_Bonusbox_Block_Checkout_Success extends Mage_Core_Block_Template
{
	protected $_template = 'bonusbox/checkout/success.phtml';
	
	protected function _beforeToHtml()
	{
		parent::_beforeToHtml();
		try {
			if (Mage::helper('bonusbox/successpage')->isOperational())
			{
				$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
				$order = Mage::getModel('sales/order')->load($orderId);
				if ($order->getId())
				{
					$response = Mage::getModel('bonusbox/client')->requestSuccessPage($order);
					$this->setSuccessPageUrl($response['success_page']['url']);
				}
				else {
					throw new Exception('No Order found for success page.');
				}
			}
			else {
				Mage::log('Bonusbox Success Page is missing config data.');
			}
		}
		catch (Exception $ex)
		{
			Mage::helper('bonusbox')->log($ex);
		}
			
	}
	
	
	protected function _toHtml()
	{
		if ($this->getSuccessPageUrl())
		{
			return parent::_toHtml();
		}
	}
}