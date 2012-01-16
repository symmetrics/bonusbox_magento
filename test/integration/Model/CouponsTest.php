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
		$code = 'bb_ImgQVPBb';
		$response = $this->getClient()->get($code);
		$this->assertEquals($code, $response['coupon']['code']);
		$this->assertNotEmpty($response['coupon']['user']['badge']['id']);
	}
	

	public function testGetFailure()
	{
		$response = $this->getClient()->get('_');
		$this->assertEmpty($response);
	}
	
	
	public function _testDeleteSuccess()
	{
		$code = 'bb_YdwulA-s';
		
		$response = $this->getClient()->delete($code);
		$this->assertEmpty($response);
	}

	/**
	 * @expectedException Bonusbox_Bonusbox_Exception
	 */
	public function _testDeleteTwice()
	{
		$code = 'bb_49kVeP88';
		try {
			$this->getClient()->delete($code);
		}
		catch (Bonusbox_Bonusbox_Exception $ex)
		{
			$this->assertTrue(false, 'Precondition for deletion of coupon code violated');
		}
		$this->getClient()->delete($code);
	}
		
	/**
	 * @expectedException Bonusbox_Bonusbox_Exception
	 */
	public function testDeleteFailure()
	{
		$response = $this->getClient()->delete('_');
		$this->assertEmpty($response);
	}
}