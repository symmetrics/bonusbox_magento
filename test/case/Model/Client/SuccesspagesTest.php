<?php
class Model_SuccesspagesTest extends MagentoTest
{
	/**
	 * @return Bonusbox_Bonusbox_Model_Client_SuccessPages
	 */
	protected function getClient()
	{
		return Mage::getModel('bonusbox/client_successPages');
	} 
	
	/**
	 * @todo check every field :(
	 */
	public function testEncodeOrder()
	{
		$order = $this->placeOrder();
    	$data = $this->getClient()->encodeOrder($order);
		$this->assertEquals($order->getIncrementId(), $data['order_number']);
		$this->assertEquals(count($order->getAllItems()), 1);
		$this->assertEquals(2, count($data['addresses']));
		$this->assertNotEmpty($data['style_url']);
	}
	
	/**
	 */
	public function testEncodeVirtualOrder()
	{
		$order = $this->placeOrder();
		$order->setIsVirtual(true);
		$data = $this->getClient()->encodeOrder($order);
		$this->assertEquals(1, count($data['addresses']));
	}
}