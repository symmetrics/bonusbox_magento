<?php
class Bonusbox_Bonusbox_Model_Client_Coupons extends Bonusbox_Bonusbox_Model_Client
{
	protected $_resourceName = 'coupons';
	
	/**
	 * @return mixed
	 */
	public function get($couponCode)
	{
		return $this->requestResource(self::METHOD_GET, true, $couponCode);
	} 
}