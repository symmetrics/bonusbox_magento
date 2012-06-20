<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category  Bonusbox
 * @package   Bonusbox_Bonusbox
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Ngoc Anh Doan <ngoc-anh.doan@cgi.com>
 * @copyright 2011-2012 BonusBox GmbH
 * @license   http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link      http://bonusbox.me
 * @link      http://github.com/symmetrics/bonusbox_magento
 */

/**
 * Custom sales rule validator model
 *
 * @category  Bonusbox
 * @package   Bonusbox_Bonusbox
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Ngoc Anh Doan <ngoc-anh.doan@cgi.com>
 * @copyright 2011-2012 BonusBox GmbH
 * @license   http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link      http://bonusbox.me
 * @link      http://github.com/symmetrics/bonusbox_magento
 */
class Bonusbox_Bonusbox_Model_SalesRule_Validator extends Mage_SalesRule_Model_Validator
{
    /**
     * Removes the coupon code if it is an bonusbox coupon code an stores it. After
     * validation the bonusbox code is set order.
     * 
     * @param int    $websiteId       Website ID
     * @param int    $customerGroupId Customer group ID
     * @param string $couponCode      Coupon code
     * 
     * @return Bonusbox_Bonusbox_Model_SalesRule_Validator
     * @see Mage_SalesRule_Model_Validator::init($websiteId, $customerGroupId, $couponCode)
     */
    public function init($websiteId, $customerGroupId, $couponCode)
    {
        if (Mage::helper('bonusbox')->isEnabled() && $couponCode) {
            if (Mage::helper('bonusbox')->isValidBonusboxCouponCode($couponCode)) {
                $this->setBonusboxCode($couponCode);
                $couponCode = null;
            } else {
                $this->setBonusboxCode(null);
            }
        }
        return parent::init($websiteId, $customerGroupId, $couponCode);
    }

    /**
     * Checks if rule contains bonusbox Conditions and if a bonusbox code is set.  
     * 
     * @param Mage_SalesRule_Model_Rule $rule Sales rule model instance
     * 
     * @return boolean
     */
    private function isBonusboxRule($rule)
    {
        if ($this->getBonusboxCode() && !$rule->getCouponCode()) {
            return $this->hasBonusboxConditions($rule->getConditions()->getConditions());
        }
    }

    /**
     * Check if $conditions contains a bonusbox condition and searches recursively
     * if a combine condition is contained.
     * 
     * @param array $conditions Conditions to check
     * 
     * @return bool|void
     */
    private function hasBonusboxConditions($conditions)
    {
        foreach ($conditions as $condition) {
            if ($condition instanceof Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox
                    || $this->hasBonusboxConditions($condition->getConditions())
            ) {
                return true;
            }
        }
    }

    /**
     * If one of the applied rules is a bonusbox rule, the code is set to the address
     * 
     * @param Mage_Sales_Model_Quote_Item_Abstract $item Sales quote item
     * 
     * @return Bonusbox_Bonusbox_Model_SalesRule_Validator
     * @see Mage_SalesRule_Model_Validator::process($item)
     */
    public function process(Mage_Sales_Model_Quote_Item_Abstract $item)
    {
        parent::process($item);
        if (!Mage::helper('bonusbox')->isEnabled() || !$this->getBonusboxCode()) {
            return $this;
        }
        $appliedRuleIds = explode(',', $item->getQuote()->getAppliedRuleIds());
        foreach ($this->_getRules() as $rule) {
            if (in_array($rule->getId(), $appliedRuleIds) && $this->isBonusboxRule($rule)) {
                $this->_setCouponDataToAddress($item, $rule);
                break;
            }
        }
        return $this;
    }

    /**
     * Set coupon code and description to address
     * 
     * @param Mage_Sales_Model_Quote_Item_Abstract $item Sales quote item
     * @param Mage_SalesRule_Model_Rule            $rule Sales rule model
     * 
     * @return void
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