<?php
class BadgesTest extends MagentoTest
{
	/**
	 * 
	 * @return Bonusbox_Bonusbox_Model_Client_Badges
	 */
	protected function getClient()
	{
		return Mage::getModel('bonusbox/client_badges');
	} 
	
	
	public function testGet()
	{
		$badges = $this->getClient()->get();
		$this->assertInternalType('array', $badges);
		$this->assertNotEmpty($badges[0]['badge']['title']);
	}
}