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
 * BONBOX-12: Overriding Mage_SalesRule_Model_Rule_Condition_Combine to make sure
 *            additional rule combines will also take affect in Magento EE.
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
class Bonusbox_Bonusbox_Model_SalesRule_Rule_Condition_Combine extends Mage_SalesRule_Model_Rule_Condition_Combine
{
    /**
     * Get inherited conditions selectors and add BonusBox specific ones.
     *
     * @return array
     * @see Mage_SalesRule_Model_Rule_Condition_Combine::getNewChildSelectOptions()
     * @see Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox::addConditions()
     */
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();
        if (Mage::helper('bonusbox')->isEnabled()) {
            $attributes = array();
            $className = 'bonusbox/salesRule_condition_bonusbox';
            $bonusBoxCondition = Mage::getModel($className)->loadAttributeOptions();
            /* @var $bonusBoxCondition Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox */
            
            foreach ($bonusBoxCondition->getAttributeOption() as $attribute => $label) {
                $attributes[] = array(
                    'value' => $className . '|' . $attribute,
                    'label' => $label
                );
            }
            
            $conditions = array_merge_recursive(
                $conditions,
                array(
                    array(
                        'label' => Mage::helper('bonusbox')->__('Bonusbox'),
                        'value' => $attributes
                    )
                )
            );
        }
        
        // 'salesrule_rule_condition_combine' has been dispatched already! Doing some
        // module specific event dispatching.
        $additional = new Varien_Object();
        Mage::dispatchEvent(
            'bonousbox_salesrule_rule_condition_combine',
            array('additional' => $additional)
        );
        if (($additionalConditions = $additional->getConditions())) {
            $conditions = array_merge_recursive($conditions, $additionalConditions);
        }

        return $conditions;
    }
}
