<?php
class Model_ConditionBonusboxTest extends MagentoTest
{
	protected function getConditions()
	{
		$options = Mage::getModel('salesrule/rule_condition_combine')->getNewChildSelectOptions();
		foreach ($options as $option)
		{
			if ($option['label'] == 'Bonusbox')
			{
				return $option['value'];
			}
		}
	}
	
	
	public function testCondition()
	{
		$conditions = $this->getConditions();
		$this->assertInternalType('array', $conditions); 
		$this->assertCount(1, $conditions); 
		
		foreach ($conditions as $condition)
		{
			list($class, $attribute) = explode('|', $condition['value']);
			$condition = Mage::getModel($class);
			$condition->setAttribute($attribute);
			$options = $condition->getValueSelectOptions();

			$this->assertInternalType('array', $options);
			$this->assertGreaterThan(0, count($options));
			$this->assertNotEmpty($condition->getInputType());
			$this->assertNotEmpty($condition->getValueElementType());
		}
	}
}