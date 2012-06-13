<?php
class Bonusbox_Bonusbox_Model_SalesRule_Validator extends Mage_SalesRule_Model_Validator
{
	/**
	 * Removes the coupon code if it is an bonusbox coupon code an stores it. After validation the bonusbox code is set order. 
	 * @param int $websiteId
	 * @param int $customerGroupId
	 * @param string $couponCode
	 * @see Mage_SalesRule_Model_Validator::init($websiteId, $customerGroupId, $couponCode)
	 */
	public function init($websiteId, $customerGroupId, $couponCode)
	{
		if (Mage::helper('bonusbox')->isEnabled() && $couponCode)
		{
			if (Mage::helper('bonusbox')->isValidBonusboxCouponCode($couponCode))
			{
				$this->setBonusboxCode($couponCode);
				$couponCode = null;
			}
			else {
				$this->setBonusboxCode(null);
			}
		}
		return parent::init($websiteId, $customerGroupId, $couponCode);
	}
	
	/**
	 * Checks if rule contains bonusbox Conditions and if a bonusbox code is set.  
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return boolean
	 */
	private function isBonusboxRule($rule)
	{
		if ($this->getBonusboxCode() && !$rule->getCouponCode())
		{
			return $this->hasBonusboxConditions($rule->getConditions()->getConditions());
		}
	}
	
	/**
	 * Check if $conditions contains a bonusbox condition and searches recursively if a combine condition is contained.
	 * @param array $conditions
	 */
	private function hasBonusboxConditions($conditions)
	{
		foreach ($conditions as $condition)
		{
			if (	$condition instanceof Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox
			 	||	$this->hasBonusboxConditions($condition->getConditions())
			) {
				return true;
			}
		}
	}
	
    /**
     * If one of the applied rules is a bonusbox rule, the code is set to the address
     * @see Mage_SalesRule_Model_Validator::process($item)
     */
    public function process(Mage_Sales_Model_Quote_Item_Abstract $item)
    {
    	parent::process($item);
 		if (!Mage::helper('bonusbox')->isEnabled() || !$this->getBonusboxCode())
    	{
    		return $this;
    	}
    	$appliedRuleIds = explode(',', $item->getQuote()->getAppliedRuleIds());
    	foreach ($this->_getRules() as $rule)
    	{
    		if (in_array($rule->getId(), $appliedRuleIds) && $this->isBonusboxRule($rule))
    		{
    			$this->_setCouponDataToAddress($item, $rule);
    			break;
    		}
    	}
    	return $this;
    }
    
    /**
     * Set coupon code and description to address
     * @param Mage_Sales_Model_Quote_Item_Abstract $item
     * @param Mage_SalesRule_Model_Rule $rule
     */
    private function _setCouponDataToAddress($item, $rule)
    {
    	$address = $this->_getAddress($item);
    	$address->setCouponCode($this->getBonusboxCode());
    	$rule->setCouponCode($this->getBonusboxCode()); // used in _addDiscountDescription
    	$this->_addDiscountDescription($address, $rule);
    	$rule->setCouponCode(null);
    }
}