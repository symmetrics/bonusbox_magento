<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Bonusbox_Bonusbox_TestController extends Mage_Checkout_OnepageController 
{
    public function successAction()
    {
    	$order = Mage::getModel('sales/order')->getResourceCollection()
    		->setPageSize(1)
    		->getFirstItem()
    	;
    	$session = $this->getOnepage()->getCheckout();
    	$session->setLastSuccessQuoteId(1);
    	$session->setLastQuoteId(1);
    	$session->setLastOrderId($order->getId());
    	
    	$this->_redirect('checkout/onepage/success');
    }
}