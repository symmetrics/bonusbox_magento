<?php
class Bonusbox_Bonusbox_Helper_Successpage extends Bonusbox_Bonusbox_Helper_Data
{
	protected $configSection = 'success_page'; 
	
	public function isOperational()
	{
		return Mage::helper('bonusbox')->isOperational() && $this->getConfig('coupon_code');
	}	
}
