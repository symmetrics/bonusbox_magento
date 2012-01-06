<?php
class Bonusbox_Bonusbox_Helper_Data extends Mage_Core_Helper_Data
{
	protected $configCode = 'bonusbox'; 

	protected $configSection = 'general'; 
	
	/**
	 * Convinience method for access to config data. 
	 * @param string $field 
	 */
	public function getConfig($field)
	{
		return Mage::getStoreConfig($this->configCode . '/' . $this->configSection . '/' . $field);
	}
	
	
	public function isEnabled()
	{
		return $this->getConfig('enabled');
	}
	
	
	public function isLive()
	{
		return $this->getConfig('live');
	}
	
	/**
	 * Checks if modulke is enabled and if keys are provided 
	 */
	public function isOperational()
	{
		return $this->isEnabled() && $this->getKey('public') && $this->getKey('secret');
	}


	/**
	 * Returns the key for the selected live mode (live|test) and the given param (public|secret)
	 * @param bool $secret
	 */
	public function getKey($secret)
	{
		$mode = $this->isLive() ? 'live' : 'test';
		$type = $secret ? 'secret' : 'public';
		return $this->getConfig($mode . '_' . $type . '_key');	
	}
	
	/**
	 * If live mode is enabled an email is sent to the configured debug mail address (see config.xml). In test mode an excption is thrown.
	 * @param string|Exception $message
	 * @throws Exception
	 * @throws Mage_Core_Exception
	 */
	public function handleError($message)
	{
		if ($this->isLive())
		{
			Mage::log((string)$message);
			try {
				$mail = new Zend_Mail('utf-8');
				$mail
					->setFrom(Mage::getStoreConfig('trans_email/ident_general/email'))
					->addTo($this->getConfig('debug_email'))
					->setSubject('Bonusbox Magento Error')
					->setBodyText((string)$message)
					->send()
				;
			}
			catch (Exception $ex)
			{
				Mage::logException($ex);
			}
		}
		else {
			if ($message instanceof Exception)
			{
				throw $message;
			}
			else {
				require_once 'Bonusbox/Bonusbox/Exception.php';
				throw new Bonusbox_Bonusbox_Exception($message);
			}
		}
	}
	
	/**
	 * Retrieves an options array with the id, title (incl. the benefit). The Badges are cached in the session. 
	 * @return array
	 */
	public function getBadgeOptions()
	{
		$session = Mage::getSingleton('bonusbox/session');
		if (!$session->getData('badge_options'))
		{
			$badges = Mage::getModel('bonusbox/client_badges')->get();
			foreach ($badges as $badge)
			{
				$badgeOptions[] = array('value' => $badge['id'], 'label' => sprintf('%s (%s)', $badge['title'], $badge['benefit']));
			}
			$session->setData('badge_options', $badgeOptions);
		}
		return $session->getData('badge_options');
	}
	
	/**
	 * Retrieves the badge id for a given coupon code. The response is cached in the session. 
	 * @param Varien_Object $quote
	 * @return int
	 */
    public function getCustomerBadge(Varien_Object $request)
    {
    	$couponCode = $request->getCouponCode();
    	if ($couponCode)
    	{
	    	$session = $this->getSession();
	    	if (!$session->getCustomerBadgeByCoupon($couponCode))
	    	{
		    	$response = Mage::getModel('bonusbox/client_coupons')->get($couponCode);
		    	$session->setCustomerBadgeByCoupon($couponCode, $response['badge']['id']);
	    	}
	    	return $session->getCustomerBadgeByCoupon($couponCode);
    	}
    }
    
    /**
     * @return Bonusbox_Bonusbox_Model_Session
     */
    public function getSession()
    {
    	return Mage::getSingleton('bonusbox/session');
    }
}