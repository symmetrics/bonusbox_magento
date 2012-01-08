<?php
class Bonusbox_Bonusbox_Helper_Successpage extends Bonusbox_Bonusbox_Helper_Data
{
	protected $configSection = 'success_page';

	
	public function isOperational()
	{
		return Mage::helper('bonusbox')->isOperational();
	}
	
	
	public function getCssUrl($appendTimestampp = true)
	{
		$url = Mage::getBaseUrl('skin');
		$url = Mage::getDesign()->getSkinUrl($this->getConfig('style_url'), array());
		if ($appendTimestampp)
		{
			$filename = $this->getCssFile();
			if (file_exists($filename))
			{
				$url .= '?' . filemtime($filename);
			}
		}
		return $url;
	} 
	
	
	public function getCssFile()
	{
		return Mage::getDesign()->getFilename($this->getConfig('style_url'), array('_type' => 'skin'));
	}
}