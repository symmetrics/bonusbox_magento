<?php
class Stub_Model_SalesRule_Validator extends Bonusbox_Bonusbox_Model_SalesRule_Validator
{
	/**
	 * Validator is a singleton and rule validation is cached -> invalidate on init 
	 * @see Bonusbox_Bonusbox_Model_SalesRule_Validator::init()
	 */
	public function init($websiteId, $customerGroupId, $couponCode)
	{
		$this->_rules = null;
		return parent::init($websiteId, $customerGroupId, $couponCode);
	}
}