<?php
class Bonusbox_Bonusbox_Block_Checkout_Success extends Mage_Core_Block_Template
{
	protected $_template = 'bonusbox/checkout/success.phtml';
	
	/**
	 * Checks if a bonusbox success url exits. After rendering the url is removed from the session. 
	 * Otherwise nothing is rendered.
	 */
	protected function _toHtml()
	{
		if ($this->getSuccessPageUrl())
		{
			$html = parent::_toHtml();
			Mage::helper('bonusbox')->getSession()->setSuccessPage(null);
			return $html;
		}
	}
	
	/**
	 * Retrieves the bonusbox success url from the session.
	 */
	public function getSuccessPageUrl()
	{
		$successPage = Mage::helper('bonusbox')->getSession()->getSuccessPage();
		return $successPage['url'];
	}
	
}