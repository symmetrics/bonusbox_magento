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
 * Custom sales rule condition model
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
class Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox extends Mage_Rule_Model_Condition_Abstract
{
    /**
     * Listener on 'salesrule_rule_condition_combine' to add additonal conditions.
     * 
     * BONBOX-12: Moved listener logic for Magento EE compatibility overridden
     *            Mage_SalesRule_Model_Rule_Condition_Combine class.
     * 
     * @param Varien_Event_Observer $observer Event observer instance
     * 
     * @return void
     */
    public function addConditions(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('bonusbox')->isEnabled()) {
            return;
        }
        $class = 'bonusbox/salesRule_condition_bonusbox';
        $condition = Mage::getModel($class);
        $condition->loadAttributeOptions();
        foreach ($condition->getAttributeOption() as $attribute => $label) {
            $attributes = array(
                array('value' => $class . '|' . $attribute, 'label' => $label)
            );
        }
        $conditions = array(array('label' => Mage::helper('bonusbox')->__('Bonusbox'), 'value' => $attributes));
        $observer->getEvent()->getAdditional()->setConditions($conditions);
    }

    /**
     * Adding badge as attribute
     * 
     * @return Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox
     */
    public function loadAttributeOptions()
    {
        $this->setAttributesMeta(
            array(
                'badge' => array(
                    'label' => Mage::helper('bonusbox')->__('Badge'),
                    'input_type' => 'select',
                    'value_element_type' => 'select'
                )
            )
        );

        foreach ($this->getAttributesMeta() as $attribute => $attributeMeta) {
            $attributes[$attribute] = $attributeMeta['label'];
        }
        $this->setAttributeOption($attributes);
        return $this;
    }

    /**
     * Getting badge attribute input type.
     * 
     * @return string
     */
    public function getInputType()
    {
        $attributesMeta = $this->getAttributesMeta();
        return $attributesMeta[$this->getAttribute()]['input_type'];
    }

    /**
     * Getting badge attribute value type.
     * 
     * @return string
     */
    public function getValueElementType()
    {
        $attributesMeta = $this->getAttributesMeta();
        return $attributesMeta[$this->getAttribute()]['value_element_type'];
    }

    /**
     * Get a list of available attribute options to select
     * 
     * @return array
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            switch ($this->getAttribute()) {
                case 'badge':
                    $options = Mage::helper('bonusbox')->getBadgeOptions();
                    break;
            }
            $this->setData('value_select_options', $options);
        }
        return $this->getData('value_select_options');
    }

    /**
     * Validate Bonusbox Badge Conditions
     *
     * @param Varien_Object $object Object holding quote.
     * 
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        if (!Mage::helper('bonusbox')->isEnabled()) {
            return;
        }
        $bonusbox = new Varien_Object();
        $bonusbox->setBadge(Mage::helper('bonusbox')->getCustomerBadge($object->getQuote()));
        return parent::validate($bonusbox);
    }
}