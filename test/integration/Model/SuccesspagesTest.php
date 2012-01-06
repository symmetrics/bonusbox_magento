<?php
class SuccesspagesTest extends MagentoTest
{
	/**
	 * 
	 * @return Bonusbox_Bonusbox_Model_Client_SuccessPages
	 */
	protected function getClient()
	{
		return Mage::getModel('bonusbox/client_successPages');
	} 
	
	
	public function testPost()
	{
		$order = $this->placeOrder();
    	$reponse = $this->getClient()->post($order);
		$this->assertArrayHasKey('success_page', $reponse);
		$this->assertArrayHasKey('url', $reponse['success_page']);
		$this->assertArrayHasKey('token', $reponse['success_page']);
	}
}