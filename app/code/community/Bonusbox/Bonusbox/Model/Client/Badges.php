<?php
class Bonusbox_Bonusbox_Model_Client_Badges extends Bonusbox_Bonusbox_Model_Client
{
	protected $_resourceName = 'badges';
	
	/**
	 * @link https://github.com/bonusboxme/api_documentation/wiki/Get--badges
	 * @return mixed
	 */
	public function get()
	{
		return $this->requestResource(self::METHOD_GET, true);
	} 
}