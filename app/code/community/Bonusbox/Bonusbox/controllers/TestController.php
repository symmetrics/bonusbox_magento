<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Bonusbox_Bonusbox_TestController extends Mage_Checkout_OnepageController 
{
	protected function getOrder()
	{
		return Mage::getModel('sales/order')->getResourceCollection()
    		->setPageSize(1)
    		->getFirstItem()
    	;
	}	
	
	
    public function successAction()
    {
    	$order = $this->getOrder();
    	$session = $this->getOnepage()->getCheckout();
//     	$session->setLastSuccessQuoteId(1);
//     	$session->setLastQuoteId(1);
    	$session->setLastOrderId($order->getId());
    	
    	$this->loadLayout();
		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('bonusbox/checkout_success'));
		$this->renderLayout();
//    	$this->_redirect('checkout/onepage/success');
    }
}