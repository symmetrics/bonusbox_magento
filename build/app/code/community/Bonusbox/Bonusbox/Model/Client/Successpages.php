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
 * Model accessing create API
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
class Bonusbox_Bonusbox_Model_Client_Successpages extends Bonusbox_Bonusbox_Model_Client
{
    /**
     * API resource name
     *
     * @var string
     */
    protected $_resourceName = 'success_pages';

    /**
     * Sets required data from address object to array
     * 
     * @param Mage_Sales_Model_Order_Address $address Order address instance
     * 
     * @return array
     */
    protected function encodeAddress(Mage_Sales_Model_Order_Address $address)
    {
        return array(
            'code' => $address->getAddressType(),
            'city' => $address->getCity(),
            'company' => $address->getCompany(),
            'region' => $address->getRegion(),
            'country' => $address->getCountry(),
            'firstname' => $address->getFirstname(),
            'lastname' => $address->getLastname(),
            'phone' => $address->getTelephone(),
            'fax' => $address->getFax(),
            'email' => $address->getEmail(),
            'street' => $address->getStreetFull(),
            'zip' => $address->getPostcode()
        );
    }

    /**
     * Sets required data from item object to array
     * 
     * @param Mage_Sales_Model_Order_Item $item Order item
     * 
     * @return array
     */
    protected function encodeItem(Mage_Sales_Model_Order_Item $item)
    {
        // ensure that all product attributes are loaded
        $product = Mage::getModel('catalog/product')->load($item->getProductId()); 
        
        return array(
            'code' => 'product',
            'sku' => $item->getSku(),
            'quantity' => round($item->getQtyOrdered()),
            'title' => $item->getName(),
            'description' => $item->getDescription(),
            'price' => $this->encodeDecimal($item->getData('price')),
            'vat_rate' => $this->encodeDecimal($item->getData('tax_percent')),
            'vat_amount' => $this->encodeDecimal($item->getData('tax_amount')),
            'landing_page' => $product->getUrlModel()->getUrl($product, array('_ignore_category' => true)),
            'image_url' => Mage::helper('catalog/image')->init($product, 'image')->__toString()
        );
    }

    /**
     * Encodes order to array
     * 
     * @param Mage_Sales_Model_Order $order Order to encode
     * 
     * @return array
     */
    public function encodeOrder(Mage_Sales_Model_Order $order)
    {
        $helper = Mage::helper('bonusbox/successpage');
        $payment = $order->getPayment();
        $vatRate = 0;
        
        if (($order->getData('shipping_amount') > 0)) {
            $vatRate = $this->encodeDecimal(
                $order->getData('shipping_tax_amount') / $order->getData('shipping_amount') * 100
            );
        }

        $addresses[] = $this->encodeAddress($order->getBillingAddress());
        if (!$order->getIsVirtual()) {
            $addresses[] = $this->encodeAddress($order->getShippingAddress());
        }
        $data = array(
            'addresses' => $addresses,
            'currency' => $order->getData('order_currency_code'),
            'order_number' => $order->getData('increment_id'),
            'discounts_used' => $order->getCouponCode() ? array($order->getCouponCode()) : null,
            'style_url' => $helper->getCssUrl(),
            'items' => array(
                array(
                    'code' => 'shipping',
                    'quantity' => $this->encodeDecimal(1),
                    'sku' => $order->getShippingMethod(),
                    'title' => $order->getShippingDescription(),
                    'price' => $this->encodeDecimal($order->getData('shipping_amount')),
                    'vat_amount' => $this->encodeDecimal($order->getData('shipping_tax_amount')),
                    'vat_rate' => $vatRate
                ),
                array(
                    'code' => 'payment',
                    'quantity' => $this->encodeDecimal(1),
                    'sku' => $payment->getMethod(),
                    'title' => $payment->getMethodInstance()->getTitle(),
                    'price' => 0,
                    'vat_amount' => 0,
                    'vat_rate' => 0
                )
            )
        );

        foreach ($order->getAllItems() as $item) {
            $data['items'][] = $this->encodeItem($item);
        }
        return $data;
    }

    /**
     * Request resource success_pages with order data.
     * Return url and token, if successful, otherwise detailed error description
     * 
     * @param Mage_Sales_Model_Order $order Sales order object
     * 
     * @return mixed
     * @link https://github.com/bonusboxme/api_documentation/wiki/CREATE-success_pages
     */
    public function post(Mage_Sales_Model_Order $order)
    {
        return $this->requestResource(self::METHOD_POST, true, null, $this->encodeOrder($order));
    }
}