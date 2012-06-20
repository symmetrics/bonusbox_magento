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
 * Model accessing coupons API
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
class Bonusbox_Bonusbox_Model_Client_Coupons extends Bonusbox_Bonusbox_Model_Client
{
    /**
     * API resource name
     *
     * @var string
     */
    protected $_resourceName = 'coupons';

    /**
     * Retrieves info for a bonusbox coupon
     * 
     * @param string $couponCode Coupon code to get infos about
     * 
     * @return mixed
     * @link https://github.com/bonusboxme/api_documentation/wiki/GET-coupons
     */
    public function get($couponCode)
    {
        return $this->requestResource(self::METHOD_GET, true, $couponCode, null, array(404));
    }

    /**
     * Deletes a bonusbox coupon
     * 
     * @param string $couponCode Coupon code to get infos about
     * 
     * @return mixed
     * @link https://github.com/bonusboxme/api_documentation/wiki/DELETE-coupons
     */
    public function delete($couponCode)
    {
        return $this->requestResource(self::METHOD_DELETE, true, $couponCode);
    }
}