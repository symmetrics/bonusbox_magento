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
 * Block to render iFrame.
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
class Bonusbox_Bonusbox_Block_Checkout_Success extends Mage_Core_Block_Template
{
    /**
     * Default block template.
     *
     * @var string
     */
    protected $_template = 'bonusbox/checkout/success.phtml';

    /**
     * Checks if a bonusbox success url exits. After rendering the url is removed
     * from the session. Otherwise nothing is rendered.
     * 
     * @return string
     */
    protected function _toHtml()
    {
        $html = '';
        
        if ($this->getSuccessPageUrl()) {
            $html = parent::_toHtml();
            Mage::helper('bonusbox')->getSession()->setSuccessPage(null);
        }
        
        return $html;
    }

    /**
     * Retrieves the bonusbox success url from the session.
     * 
     * @return string|null
     */
    public function getSuccessPageUrl()
    {
        $successPage = Mage::helper('bonusbox')->getSession()->getSuccessPage();
        
        return isset($successPage['url']) ? $successPage['url'] : null;
    }
}