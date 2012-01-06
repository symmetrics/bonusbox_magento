<?php
class Bonusbox_Bonusbox_Model_Client extends Varien_Http_Client
{
	const CONTENT_TYPE = 'application/json';
	
	const 
		METHOD_POST = 'POST',
		METHOD_PUT = 'PUT',
		METHOD_GET = 'GET',
		METHOD_DELETE = 'DELETE'
	;
	
	protected $_resourceName;
	
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
	 * Request a resource with given method and data.
	 * @param string $method
	 * @param bool $useSecretKey - Flag for secure/public key
	 * @param mixed $queryData
	 * @param mixed $rawData
	 */
	public function requestResource($method, $useSecretKey, $queryData = null, $rawData = null)
	{
		try {
			$this->setAuth(Mage::helper('bonusbox')->getKey($useSecretKey));
			$this->setUri(Mage::helper('bonusbox')->getConfig('url') . $this->_resourceName);
			if ($queryData)
			{
				// @todo if array is provided
				$this->setUri($this->getUri() . '/' . $queryData);
			}
			if ($rawData)
			{
				$this->setRawData($this->encodeData($rawData));
			} 
			$response = $this->request($method);
			if (strpos($response->getStatus(), '2') === 0) # codes in the 2xx range indicate success
			{
				$response = $this->decodeData($response->getBody());
				return $response;
			}
			require_once 'Bonusbox/Bonusbox/Exception.php';
			throw new Bonusbox_Bonusbox_Exception("Invalid Response\n" . (string)$this . "\n\n" . (string)$response);
		}
		catch (Exception $ex)
		{
			Mage::helper('bonusbox')->handleError($ex);
		}
	}
	
	/**
	 * Formats decimal values to int by multiplying and rounding
	 * @param decimal $value
	 * @param int $precision
	 * @return int
	 */
	public function encodeDecimal($value, $precision = 2)
	{
		return (int)round($value * pow(10, $precision));
	}
	
	/**
	 * Super class does not support __toString
	 * @return string
	 * @codeCoverageIgnore
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