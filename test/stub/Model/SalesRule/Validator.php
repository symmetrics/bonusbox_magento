<?php
class Stub_Model_SalesRule_Validator extends Bonusbox_Bonusbox_Model_SalesRule_Validator
{
	public function init($websiteId, $customerGroupId, $couponCode)
	{
		$this->_rules = null;
		return parent::init($websiteId, $customerGroupId, $couponCode);
	}
}