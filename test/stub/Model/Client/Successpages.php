<?php
class Stub_Model_Client_Successpages extends Bonusbox_Bonusbox_Model_Client_Successpages
{
	public function post(Mage_Sales_Model_Order $order)
	{
		return array('success_page' => array(
			'url' => 'https://fb.bonusbox.me/success_pages/lZqKRClweRBuU9eTgTd-' . $order->getIncrementId(),
			'token' => 'fdsfs'
		));
	} 
}