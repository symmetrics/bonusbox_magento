<?php
class Bonusbox_Bonusbox_Helper_Data extends Mage_Core_Helper_Data
{
	protected $configCode = 'bonusbox'; 

	protected $configSection = 'general'; 
	
	public function getConfig($key)
	{
		return Mage::getStoreConfig($this->configCode . '/' . $this->configSection . '/' . $key);
	}
	
	
	public function isEnabled()
	{
		return $this->getConfig('enabled');
	}
	
	
	public function getKey($secret)
	{
		$mode = $this->getConfig('live_mode') ? 'live' : 'test';
		$type = $secret ? 'secret' : 'public';
		return $this->getConfig($mode . '_' . $type . '_key');	
	}
	
	
	public function log($message)
	{
		if ($this->getConfig('live_mode'))
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
				throw new Mage_Core_Exception($message);
			} 
		}
	}
}
