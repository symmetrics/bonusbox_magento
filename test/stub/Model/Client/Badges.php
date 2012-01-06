<?php
class Stub_Model_Client_Badges extends Bonusbox_Bonusbox_Model_Client_Badges
{
	const GOLD = 3; 
	const SILVER = 23; 
	const BRONZE = 1; 
	
	/**
	 * @return mixed
	 */
	public function get()
	{
		return array(
			array('id' => self::BRONZE, 'title' => 'Bronze', 'benefit' => '5% Discount'),
			array('id' => self::SILVER, 'title' => 'Silver', 'benefit' => '10% Discount'),
			array('id' => self::GOLD, 'title' => 'Gold', 'benefit' => '15% Discount')
		);
	}
}