<?php
class Bonusbox_Bonusbox_Model_Client_Coupons extends Bonusbox_Bonusbox_Model_Client
{
	protected $_resourceName = 'coupons';

	/**
	 * Retrieves info for a bonusbox coupon
	 * @link https://github.com/bonusboxme/api_documentation/wiki/GET-coupons
	 * @param string $couponCode
	 * @return mixed
	 */
	public function get($couponCode)
	{
		return $this->requestResource(self::METHOD_GET, true, $couponCode);
	} 


	/**
	 * Deletes a bonusbox coupon
	 * @link https://github.com/bonusboxme/api_documentation/wiki/DELETE-coupons
	 * @param string $couponCode
	 * @return mixed
	 */
	public function delete($couponCode)
	{
		return $this->requestResource(self::METHOD_DELETE, true, $couponCode);
	}
}