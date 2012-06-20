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
 * Event listener to submit order data to API
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
class Bonusbox_Bonusbox_Model_Sales_Observer
{
    /**
     * Forwards order to bonuxbox and saves url for success page iframe in session.
     * Deletes bonusbox coupon code.
     * 
     * @param Varien_Event_Observer $observer Event observer
     * 
     * @return void
     */
    public function forwardOrder(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        if (Mage::helper('bonusbox')->isEnabled()) {
            if (Mage::helper('bonusbox/successpage')->isOperational()) {
                // send order to bonusbox
                $response = Mage::getModel('bonusbox/client_successpages')->post($order);
                Mage::helper('bonusbox')->getSession()->setSuccessPage($response['success_page']);

                // delete coupon code from bonusbox
                if (Mage::helper('bonusbox')->isValidBonusboxCouponCode($order->getCouponCode())) {
                    Mage::getModel('bonusbox/client_coupons')->delete($order->getCouponCode());
                }

                // invalidate Customer Badge Cache
                Mage::helper('bonusbox')->getSession()->setCustomerBadgesByCoupon(null);
            } else {
                Mage::log('Bonusbox Success Page is missing config data.');
            }
        }
    }
}