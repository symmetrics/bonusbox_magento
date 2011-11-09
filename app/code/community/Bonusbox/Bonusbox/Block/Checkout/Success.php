<?php
class Bonusbox_Bonusbox_Block_Checkout_Success extends Mage_Core_Block_Template
{
	protected $_template = 'bonusbox/checkout/success.phtml';
	
	protected function _beforeToHtml()
	{
		parent::_beforeToHtml();
		if (Mage::helper('bonusbox/successpage')->isOperational())
		{
			$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
			if ($orderId) 
			{
				$order = Mage::getModel('sales/order')->load($orderId);
				if ($order->getId())
				{
					$response = Mage::getModel('bonusbox/client')->requestSuccessPage($order);
					$this->setSuccessPageUrl($response['success_page']['url']);
				}
			}
		}
		else {
			Mage::log('Bonusbox Success Page is missing config data.');
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