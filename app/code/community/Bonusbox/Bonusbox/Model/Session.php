<?php
class Bonusbox_Bonusbox_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        $this->init('bonusbox');
    }
    
    
    public function getCustomerBadgeByCoupon($couponCode)
    {
    	$badges = $this->getCustomerBadgesByCoupon();
    	return $badges[$couponCode];
    }
    
    
    public function setCustomerBadgeByCoupon($couponCode, $badge)
    {
    	$badges = $this->getCustomerBadgesByCoupon();
    	$badges[$couponCode] = $badge;
    	$this->setCustomerBadgesByCoupon($badges);
    	return $this;
    }
}