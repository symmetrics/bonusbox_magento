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
 * @link      http://github.com/bonusboxme/magento
 */

require_once 'Mage/Checkout/controllers/OnepageController.php';

/**
 * Testing controller
 *
 * @category  Bonusbox
 * @package   Bonusbox_Bonusbox
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Ngoc Anh Doan <ngoc-anh.doan@cgi.com>
 * @copyright 2011-2012 BonusBox GmbH
 * @license   http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link      http://bonusbox.me
 * @link      http://github.com/bonusboxme/magento
 * @codeCoverageIgnore
 */
class Bonusbox_Bonusbox_TestController extends Mage_Checkout_OnepageController
{
    /**
     * Getting order object.
     * 
     * @return Mage_Sales_Model_Order
     */
    protected function getOrder()
    {
        return Mage::getModel('sales/order')
            ->getResourceCollection()
            ->setPageSize(1)
            ->getFirstItem();
    }
    
    /**
     * Order success action.
     * 
     * @return void
     */
    public function successAction()
    {
        $order = $this->getOrder();
        Mage::dispatchEvent('sales_model_service_quote_submit_after', array('order' => $order));

        $this->loadLayout();
        $this->getLayout()
            ->getBlock('content')
            ->append($this->getLayout()->createBlock('bonusbox/checkout_success'));
        
        $this->renderLayout();
    }
}
