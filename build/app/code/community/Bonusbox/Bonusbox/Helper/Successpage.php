<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category  Bonusbox
 * @package   Bonusbox_Bonusbox
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Ngoc Anh Doan <ngoc-anh.doan@cgi.com>
 * @copyright 2011-2012 BonusBox GmbH
 * @license   http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link      http://bonusbox.me
 * @link      http://github.com/symmetrics/bonusbox_magento
 */

/**
 * Module helper class for checkout success view.
 *
 * @category  Bonusbox
 * @package   Bonusbox_Bonusbox
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Ngoc Anh Doan <ngoc-anh.doan@cgi.com>
 * @copyright 2011-2012 BonusBox GmbH
 * @license   http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link      http://bonusbox.me
 * @link      http://github.com/symmetrics/bonusbox_magento
 */
class Bonusbox_Bonusbox_Helper_Successpage extends Bonusbox_Bonusbox_Helper_Data
{
    /**
     * Part of config XML node.
     *
     * @var string 
     */
    protected $_configSection = 'success_page';

    /**
     * Checks if module is enabled and if keys are provided.
     * 
     * @return bool
     */
    public function isOperational()
    {
        return Mage::helper('bonusbox')->isOperational();
    }

    /**
     * Generates CSS URL
     * 
     * @param bool $appendTimestamp Flag to append a timestamp to CSS resource
     * 
     * @return string
     */
    public function getCssUrl($appendTimestamp = true)
    {
        $url = Mage::getDesign()->getSkinUrl($this->getConfig('style_url'), array());
        if ($appendTimestamp) {
            $filename = $this->getCssFile();
            if (file_exists($filename)) {
                $url .= '?' . filemtime($filename);
            }
        }
        return $url;
    }
    
    /**
     * Gets CSS file name
     * 
     * @return string
     */
    public function getCssFile()
    {
        return Mage::getDesign()->getFilename($this->getConfig('style_url'), array('_type' => 'skin'));
    }
}