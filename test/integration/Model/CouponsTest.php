<?php
class CouponsTest extends MagentoTest
{
	/**
	 * 
	 * @return Bonusbox_Bonusbox_Model_Client_Coupons
	 */
	protected function getClient()
	{
		return Mage::getModel('bonusbox/client_coupons');
	} 
	
	
	public function testGetSuccess()
	{
		$code = 'Winter';
		$response = $this->getClient()->get($code);
		$this->assertEquals($code, $response['coupon']);
		$this->assertNotEmpty($response['badge']['id']);
	}
}