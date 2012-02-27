<?php
class Bonusbox_Bonusbox_Helper_Data extends Mage_Core_Helper_Data
{
	protected $configCode = 'bonusbox'; 

	protected $configSection = 'general'; 
	
	/**
	 * Convinience method for access to config data. 
	 * @param string $field 
	 */
	public function getConfig($field, $storeId = null)
	{
		return Mage::getStoreConfig($this->configCode . '/' . $this->configSection . '/' . $field, $storeId);
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
	public function getKey($secret, $storeId = null)
	{
		$mode = $this->isLive() ? 'live' : 'test';
		$type = $secret ? 'secret' : 'public';
		return $this->getConfig($mode . '_' . $type . '_key', $storeId);	
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
	 * Retrieves an options array with the id, title (incl. the benefit). 
	 * The Badges are cached in the session. 
	 * @TODO refactor and extract to badge helper class
	 * @return array
	 */
	public function getBadgeOptions()
	{
		$session = Mage::getSingleton('bonusbox/session');
		if (!$session->getData('badge_options'))
		{
			$secret = true;
			foreach (Mage::app()->getGroups() as $storeGroup)
			{
				$apiKey = $this->getKey($secret, $storeGroup->getDefaultStoreId());
				$storeGroups[$apiKey] = $storeGroup;
			}
			
			$client = Mage::getModel('bonusbox/client_badges');
			foreach ($storeGroups as $storeGroup)
			{
				$badges = $client->setStoreId($storeGroup->getDefaultStoreId())->get();
				foreach ($badges as $badge)
				{
					$badge = $badge['badge'];
					$label = $badge['title'];
					if ($badge['benefit'])
					{
						$label = sprintf('%s (%s)', $label, $badge['benefit']);
					} 
					if (count($storeGroups) > 1)
					{
						$label = sprintf('%s - %s', $storeGroup->getName(), $label);
					}
					$badgeOptions[] = array('value' => $badge['id'], 'label' => $label);
				}
			}
			$session->setData('badge_options', $badgeOptions);
		}
		return $session->getData('badge_options');
	}
	
	/**
	 * Checks if coupon code is a valid bonusbox code
	 * @param string $couponCode
	 * @return bool
	 */
	public function isValidBonusboxCouponCode($couponCode)
	{
		$request = new Varien_Object();
		$request->setCouponCode($couponCode);
		return $this->getCustomerBadge($request) !== null;
	}
	
	/**
	 * Retrieves the badge id for a given coupon code. 
	 * The response is cached in the session. 
	 * @TODO extract to badge helper class
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
	    		$session->setCustomerBadgeByCoupon($couponCode, isset($response['coupon']['user']['badge']['id']) ? $response['coupon']['user']['badge']['id'] : null);
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