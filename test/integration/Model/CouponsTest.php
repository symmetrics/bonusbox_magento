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
	
	/**
	 * @TODO add valid codes
	 */
	public function testGetSuccess()
	{
		$code = 'Winter';
		$response = $this->getClient()->get($code);
		$this->assertEquals($code, $response['coupon']);
		$this->assertNotEmpty($response['badge']['id']);
	}
	

	public function testGetFailure()
	{
		$this->getClient()->get('_');
		$this->assertEmpty($response);
	}
	
	
	public function testDeleteSuccess()
	{
		$code = 'Winter';
		$response = $this->getClient()->delete($code);
		$this->assertEquals($code, $response['coupon']);
	}


	public function testDeleteFailure()
	{
		$this->getClient()->delete('_');
		$this->assertEmpty($response);
	}
}