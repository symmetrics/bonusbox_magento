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
 * Default module helper class.
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
class Bonusbox_Bonusbox_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Part of config XML node.
     *
     * @var string 
     */
    protected $_configCode = 'bonusbox';

    /**
     * Part of config XML node.
     *
     * @var string 
     */
    protected $_configSection = 'general';

    /**
     * Convinience method for access to config data.
     * 
     * @param string $field   Config XML node
     * @param int    $storeId Store ID
     * 
     * @return string
     */
    public function getConfig($field, $storeId = null)
    {
        return Mage::getStoreConfig($this->_configCode . '/' . $this->_configSection . '/' . $field, $storeId);
    }

    /**
     * Check if it is enabled.
     * 
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig('enabled');
    }

    /**
     * Check if in live mode.
     * 
     * @return bool
     */
    public function isLive()
    {
        return $this->getConfig('live');
    }

    /**
     * Checks if module is enabled and if keys are provided.
     * 
     * @return bool
     */
    public function isOperational()
    {
        return $this->isEnabled() && $this->getKey('public') && $this->getKey('secret');
    }

    /**
     * Returns the key for the selected live mode (live|test) and the given param
     * (public|secret).
     * 
     * @param bool $secret  Flag indicating secret or public type
     * @param int  $storeId Store ID
     * 
     * @return string
     */
    public function getKey($secret, $storeId = null)
    {
        $mode = $this->isLive() ? 'live' : 'test';
        $type = $secret ? 'secret' : 'public';
        return $this->getConfig($mode . '_' . $type . '_key', $storeId);
    }

    /**
     * If live mode is enabled an email is sent to the configured debug mail
     * address (see config.xml). In test mode an excption is thrown.
     * 
     * @param string|Exception $message Message/Exception to log
     * 
     * @return void
     * @throws Bonusbox_Bonusbox_Exception
     */
    public function handleError($message)
    {
        if ($this->isLive()) {
            Mage::log((string) $message);
            try {
                $mail = new Zend_Mail('utf-8');
                $mail
                    ->setFrom(Mage::getStoreConfig('trans_email/ident_general/email'))
                    ->addTo($this->getConfig('debug_email'))
                    ->setSubject('Bonusbox Magento Error')
                    ->setBodyText((string) $message)
                    ->send();
            } catch (Exception $ex) {
                Mage::logException($ex);
            }
        } else {
            Mage::log('TEST ' . (string) $message);
            if ($message instanceof Exception) {
                throw $message;
            } else {
                require_once 'Bonusbox/Bonusbox/Exception.php';
                throw new Bonusbox_Bonusbox_Exception($message);
            }
        }
    }

    /**
     * Retrieves an options array with the id, title (incl. the benefit). 
     * The Badges are cached in the session.
     * 
     * @return array
     * 
     * @todo refactor and extract to badge helper class
     */
    public function getBadgeOptions()
    {
        $session = Mage::getSingleton('bonusbox/session');
        if (!$session->getData('badge_options')) {
            $secret = true;
            foreach (Mage::app()->getGroups() as $storeGroup) {
                $apiKey = $this->getKey($secret, $storeGroup->getDefaultStoreId());
                $storeGroups[$apiKey] = $storeGroup;
            }

            $client = Mage::getModel('bonusbox/client_badges');
            foreach ($storeGroups as $storeGroup) {
                $badges = $client->setStoreId($storeGroup->getDefaultStoreId())->get();
                foreach ($badges as $badge) {
                    $badge = $badge['badge'];
                    $label = $badge['title'];
                    if ($badge['benefit']) {
                        $label = sprintf('%s (%s)', $label, $badge['benefit']);
                    }
                    if (count($storeGroups) > 1) {
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
     * Checks if coupon code is a valid bonusbox code.
     * 
     * @param string $couponCode Coupon code
     * 
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
     * 
     * @param Varien_Object $request Generic data object.
     * 
     * @return int|void
     * @todo extract to badge helper class
     */
    public function getCustomerBadge(Varien_Object $request)
    {
        $couponCode = $request->getCouponCode();
        if ($couponCode) {
            $session = $this->getSession();
            if (!$session->getCustomerBadgeByCoupon($couponCode)) {
                $response = Mage::getModel('bonusbox/client_coupons')->get($couponCode);
                $badge = isset($response['coupon']['user']['badge']['id']) ?
                    $response['coupon']['user']['badge']['id'] :
                    null;
                
                $session->setCustomerBadgeByCoupon($couponCode, $badge);
            }
            return $session->getCustomerBadgeByCoupon($couponCode);
        }
    }

    /**
     * Public accessor to bonusbox session.
     * 
     * @return Bonusbox_Bonusbox_Model_Session
     */
    public function getSession()
    {
        return Mage::getSingleton('bonusbox/session');
    }

}