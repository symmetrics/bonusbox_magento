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
	

	/**
	* @expectedException Bonusbox_Bonusbox_Exception
	*/
	public function testGetFailure()
	{
		$this->getClient()->get('_');
	}
	
	
	public function testDeleteSuccess()
	{
		$code = 'Winter';
		$response = $this->getClient()->delete($code);
		$this->assertEquals($code, $response['coupon']);
	}


	/**
	* @expectedException Bonusbox_Bonusbox_Exception
	*/
	public function testDeleteFailure()
	{
		$this->getClient()->delete('_');
	}
}