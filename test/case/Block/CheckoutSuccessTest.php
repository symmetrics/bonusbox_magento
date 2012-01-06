<?php
class Block_CheckoutSuccessTest extends MagentoTest
{
	public function render()
	{
		return Mage::getSingleton('core/layout')->createBlock('bonusbox/checkout_success')->toHtml();
	}
	
	
	public function testSuccess()
	{
 		$this->placeOrder();
 		$html1 = $this->render();
		$this->assertRegExp('~iframe~', $html1);
		$this->assertEmpty(Mage::helper('bonusbox')->getSession()->getSuccessPage());
		
		$this->placeOrder();
		$html2 = $this->render();
		$this->assertNotEquals($html1, $html2);
	}

	
	public function testEmpty()
	{
		$this->assertEmpty($this->render());
	}
}