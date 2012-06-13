<?php
class Bonusbox_Bonusbox_Model_Client_Successpages extends Bonusbox_Bonusbox_Model_Client
{
	protected $_resourceName = 'success_pages';
	
	/**
	 * Sets required data from address object to array 
	 * @param Mage_Sales_Model_Order_Address $address
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
	* @param Mage_Sales_Model_Order_Item $item
	* @return array
	*/
	protected function encodeItem(Mage_Sales_Model_Order_Item $item)
	{
		$product = Mage::getModel('catalog/product')->load($item->getProductId()); // ensure that all product attributes are loaded
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
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	public function encodeOrder(Mage_Sales_Model_Order $order)
	{
		$helper = Mage::helper('bonusbox/successpage');
		$payment = $order->getPayment();
		
		$addresses[] = $this->encodeAddress($order->getBillingAddress());
		if (!$order->getIsVirtual())
		{
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
					'vat_rate' => $order->getData('shipping_amount') > 0 ? $this->encodeDecimal($order->getData('shipping_tax_amount') / $order->getData('shipping_amount') * 100) : 0  
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
		
		foreach ($order->getAllItems() as $item)
		{
			$data['items'][] = $this->encodeItem($item); 
		}
		return $data;
	}
	
	/**
	 * Request resource success_pages with order data
	 * Return url and token, if successful, otherwise detailed error description
	 * @link https://github.com/bonusboxme/api_documentation/wiki/CREATE-success_pages
	 * @param Mage_Sales_Model_Order $order
	 * @return mixed
	 */
	public function post(Mage_Sales_Model_Order $order)
	{
		return $this->requestResource(self::METHOD_POST, true, null, $this->encodeOrder($order));
	}
}