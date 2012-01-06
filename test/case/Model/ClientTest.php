<?php
class Model_ClientTest extends MagentoTest
{
	/**
	 * @return Bonusbox_Bonusbox_Model_Client
	 */
	public function getClient()
	{
		return Mage::getModel('bonusbox/client');
	}
	
	public function testEncodeDecodeData()
	{
		$raw = array('foo' => 'bar');
		$encoded = $this->getClient()->encodeData($raw);
		$this->assertEquals('{"foo":"bar"}', $encoded);
		$decoded = $this->getClient()->decodeData($encoded);
		$this->assertEquals($decoded, $raw);
	}


	public function testEncodeDecimal()
	{
 		$this->assertEquals(1200, $this->getClient()->encodeDecimal(12));	
 		$this->assertEquals(1230, $this->getClient()->encodeDecimal(12.3));	
 		$this->assertEquals(1234, $this->getClient()->encodeDecimal(12.34));	
 		$this->assertEquals(1235, $this->getClient()->encodeDecimal(12.3456));
		$this->assertEquals(123456, $this->getClient()->encodeDecimal(12.3456, 4));	
	}
	
	/**
	*/
	public function testValidRequest()
	{
		$response = $this->getClient()->requestResource('valid', true, 'queryData', array('foo' => 'bar'));
		$this->assertInternalType('array', $response);
	}
	
	/**
	* @expectedException Bonusbox_Bonusbox_Exception
	*/
	public function testInvalidRequest()
	{
		$response = $this->getClient()->requestResource('invalid', true, 'queryData', array('foo' => 'bar'));
	}
}