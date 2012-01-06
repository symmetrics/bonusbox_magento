<?php
class Bonusbox_Bonusbox_Model_SalesRule_Condition_Bonusbox extends Mage_Rule_Model_Condition_Abstract
{
	public function addConditions(Varien_Event_Observer $observer)
	{
		$class = 'bonusbox/salesrule_condition_bonusbox';
		$condition = Mage::getModel($class);
		$condition->loadAttributeOptions();
		foreach ($condition->getAttributeOption() as $attribute => $label)
		{
			$attributes = array(
	            array('value' => $class . '|' . $attribute, 'label' => $label)
	        );
		}
 		$conditions = array(array('label' => Mage::helper('bonusbox')->__('Bonusbox'), 'value' => $attributes));
 		$observer->getEvent()->getAdditional()->setConditions($conditions);
	}
	
	
	public function loadAttributeOptions()
    {
		$this->setAttributesMeta(array(
			'badge' => array(
				'label' => Mage::helper('bonusbox')->__('Badge'),
				'input_type' => 'select',
				'value_element_type' => 'select'
			)
		));
    	
    	foreach ($this->getAttributesMeta() as $attribute => $attributeMeta)
    	{
        	$attributes[$attribute] = $attributeMeta['label'];
    	}
    	$this->setAttributeOption($attributes);
        return $this;
    }
    

    public function getInputType()
    {
    	$attributesMeta = $this->getAttributesMeta();
    	return $attributesMeta[$this->getAttribute()]['input_type'];
    }
    
    
    public function getValueElementType()
    {
    	$attributesMeta = $this->getAttributesMeta();
    	return $attributesMeta[$this->getAttribute()]['value_element_type'];
    }
    
    
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) 
        {
            switch ($this->getAttribute()) 
            {
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
     * @param Varien_Object $object
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
    	$bonusbox = new Varien_Object();
    	$bonusbox->setBadge(Mage::helper('bonusbox')->getCustomerBadge($object->getQuote()));
        return parent::validate($bonusbox);
    }
}