<?php
class Stub_Model_Client_Coupons extends Bonusbox_Bonusbox_Model_Client_Coupons
{
	const GOLD = 'GOLD'; 
	const SILVER = 'SILVER'; 
	const BRONZE = 'BRONZE'; 
	const INVALID_RULE = 'INVALID_RULE'; 
		
	/**
	 * @return mixed
	 */
	public function get($couponCode)
	{
		switch ($couponCode)
		{
			case self::BRONZE:
				return array(
					"user" => array(
						"id" => 12923,
						"credits" => 8000
					),
					"badge" => array(
					    "id" => Stub_Model_Client_Badges::BRONZE,
						"title" => "bronze badge",
					    "discount" => 1500
					 ),
					 "coupon" => $couponCode,
			  		 "shop" => array(
			    		"id" => 123
					 )
				);
			case self::SILVER:
				return array(
					"user" => array(
						"id" => 12923,
						"credits" => 8000
					),
					"badge" => array(
					    "id" => Stub_Model_Client_Badges::SILVER,
						"title" => "bronze badge",
					    "discount" => 1500
					 ),
					 "coupon" => $couponCode,
			  		 "shop" => array(
			    		"id" => 123
					 )
				);
			case self::GOLD:
				return array(
					"user" => array(
						"id" => 12923,
						"credits" => 8000
					),
					"badge" => array(
					    "id" => Stub_Model_Client_Badges::GOLD,
						"title" => "bronze badge",
					    "discount" => 1500
					 ),
					 "coupon" => $couponCode,
			  		 "shop" => array(
			    		"id" => 123
					 )
				);
			case self::INVALID_RULE:
				return array(
					"user" => array(
						"id" => 12923,
						"credits" => 8000
					),
					"badge" => array(
					    "id" => -1,
						"title" => "badge without valide salesrule",
					    "discount" => 1500
					 ),
					 "coupon" => $couponCode,
			  		 "shop" => array(
			    		"id" => 123
					 )
				);
		}
	} 
	
	public function delete($couponCode)
	{
		return $this->get($couponCode);
	}
}