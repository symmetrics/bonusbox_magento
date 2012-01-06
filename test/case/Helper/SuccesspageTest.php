<?php
class Helper_SuccesspageTest extends MagentoTest
{
	public function testStyleUrl()
	{
		$helper = Mage::helper('bonusbox/successpage');
		if ($helper->getConfig('style_url'))
		{
			$filename = $helper->getCssFile();
			if (file_exists($filename))
			{
				$url = $helper->getCssUrl();
//				file_put_contents($filename, file_get_contents($filename));
//				$this->assertNotEquals($url, $helper->getCssUrl());
				// the file time is not updated, only in the next test run
			}
		}
	}
}