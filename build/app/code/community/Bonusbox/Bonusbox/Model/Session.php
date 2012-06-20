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
 * Custom module session model
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
class Bonusbox_Bonusbox_Model_Session extends Mage_Core_Model_Session_Abstract
{
    /**
     * Initialize session space
     */
    public function __construct()
    {
        $this->init('bonusbox');
    }

    /**
     * Getting customer badge by coupon code
     * 
     * @param string $couponCode Coupon code
     * 
     * @return mixed
     */
    public function getCustomerBadgeByCoupon($couponCode)
    {
        $badges = $this->getCustomerBadgesByCoupon();
        return isset($badges[$couponCode]) ? $badges[$couponCode] : null;
    }

    /**
     * Set customer badge by coupon
     * 
     * @param string $couponCode Coupon code
     * @param mixed  $badge      Badge to apply
     * 
     * @return Bonusbox_Bonusbox_Model_Session
     */
    public function setCustomerBadgeByCoupon($couponCode, $badge)
    {
        $badges = $this->getCustomerBadgesByCoupon();
        $badges[$couponCode] = $badge;
        $this->setCustomerBadgesByCoupon($badges);
        return $this;
    }
}