<?php
class Bonusbox_Bonusbox_Model_Client extends Varien_Http_Client
{
	const CONTENT_TYPE = 'application/json';
	
	/**
	 * set default config data
	 */
	public function __construct()
	{
		parent::__construct();
		$this
 			->setHeaders('Accept', Mage::helper('bonusbox')->getConfig('accept_header'))
 			->setHeaders('Content-Type', self::CONTENT_TYPE)
		;
	}
	
	/**
	 * Encodes data to json format 
	 * @param mixed $body
	 */
	public function encodeData($body)
	{
		return json_encode($body);
	}

	/**
	 * Decodes data from json format
	 * @param unknown_type $body
	 */
	public function decodeData($body)
	{
		return json_decode($body, true);
	}
	
	/**
	 * Request a resource with given data.
	 * @param string $resource
	 * @param bool $secret - Flag for secure/public key
	 * @param mixed $data
	 */
	public function requestResource($resource, $secret, $data = null)
	{
		try {
			$this
 				->setAuth(Mage::helper('bonusbox')->getKey($secret))
				->setUri(Mage::helper('bonusbox')->getConfig('url') . $resource)
				->setRawData($this->encodeData($data)) 
			;
			$response = $this->request('POST');
			if (strpos($response->getStatus(), '2') === 0) # codes in the 2xx range indicate success
			{
				return $this->decodeData($response->getBody());
			}
			else {
				Mage::helper('bonusbox')->log((string)$this . "\n\n" . (string)$response);
			}
		}
		catch (Exception $ex)
		{
			Mage::helper('bonusbox')->log($ex);
		}
	}
	
	/**
	 * Sets required data from address object to array 
	 * @param Mage_Sales_Model_Order_Address $address
	 */
	protected function getAddressPost(Mage_Sales_Model_Order_Address $address)
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
	 * Formats decimal values to int by multiplying and rounding
	 * @param decimal $value
	 * @param int $precision
	 * @return int
	 */
	protected function encodeDecimal($value, $precision = 2)
	{
		return round($value * pow(10, $precision));
	}
	
	/**
	 * Request resource success_pages with order data
	 * Return url and token, if successful, otherwise detailed error description
	 * @param Mage_Sales_Model_Order $order
	 * @return mixed
	 */
	public function requestSuccessPage(Mage_Sales_Model_Order $order)
	{
		$payment = $order->getPayment();
		$helper = Mage::helper('bonusbox/successpage');

		$data = array(
			'addresses' => array(
				$this->getAddressPost($order->getBillingAddress()),
				$this->getAddressPost($order->getShippingAddress())
			),
			'discount' => array(
				'token' => $helper->getConfig('coupon_code'),
				'expires_at' => $helper->getConfig('coupon_expires_at'),
				'title' => $helper->getConfig('coupon_title'),
				'description' => $helper->getConfig('coupon_description')
			),
			'discounts_used' => $order->getCouponCode() ? array($order->getCouponCode()) : null,
			'items' => array(
				array(
					'code' => 'shipping',
					'quantity' => $this->encodeDecimal(1),
					'sku' => $order->getShippingMethod(),
					'title' => $order->getShippingDescription(),
					'price' => $this->encodeDecimal($order->getData('shipping_amount')),
					'vat_amount' => $this->encodeDecimal($order->getData('shipping_tax_amount')), 
					'vat_rate' => $order->getData('shipping_amount') > 0 ? $this->encodeDecimal($order->getData('shipping_tax_amount') / ($order->getData('shipping_amount'))) : 0  
				),
				array(
					'code' => 'payment',
					'quantity' => $this->encodeDecimal(1),
					'sku' => $payment->getMethod(),
					'title' => $payment->getMethodInstance()->getTitle(),
					'price' => 0,
					'vat_amount' => 0, 
					'vat_rate' => 0
				),
			),
			'currency' => $order->getData('order_currency_code'),
			'order_number' => $order->getData('increment_id'),
			'new_user_text' => $helper->getConfig('new_user_text'),
			'bonusbox_user_text' => $helper->getConfig('bonusbox_user_text'),
			'style_url' => Mage::getDesign()->getSkinUrl($helper->getConfig('style_url'), array()) 
		);
		
		foreach ($order->getAllItems() as $item)
		{
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$data['items'][] = array(
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
		return $this->requestResource('success_pages', true, $data);
	} 
	
	/**
	 * Super class does not support __toString
	 * @return string
	 */
	public function __toString()
	{
		foreach ($this->headers as $header)
		{
			$headers[] = $header[0] . ': ' . $header[1]; 
		}
		return implode("\n", array(
			(string)$this->getUri(),
 			implode("\n", $headers),
 			'Authorization: ' . $this->auth['type'] . ' ' . $this->auth['user'] . ':' . $this->auth['password'],
 			$this->raw_post_data
		));
	}
}