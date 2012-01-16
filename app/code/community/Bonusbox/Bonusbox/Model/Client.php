<?php
class Bonusbox_Bonusbox_Model_Client extends Zend_Http_Client
{
	const CONTENT_TYPE = 'application/json';
	
	const 
		METHOD_POST = 'POST',
		METHOD_PUT = 'PUT',
		METHOD_GET = 'GET',
		METHOD_DELETE = 'DELETE'
	;

	/**
	 * Name of requested resource -> is set by sub classes
	 * @var string
	 */
	protected $_resourceName;
	
	/**
	 * determines store and which config data will be used 
	 * @TODO resolve with config object 
	 * @var int
	 */
	protected $_storeId;
	
	/**
	 * object is created with empty array by magento -> if constructor is invoked with empty array, it requires to contain a valid url  
	 */
	public function __construct()
	{
		parent::__construct();
		$adapter = new Zend_Http_Client_Adapter_Curl();
		$this->setAdapter($adapter);
		$adapter->setConfig(array(
			'curloptions' => array(
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 0
			)
		));
	}
	
	/**
	 * Initialize request
	 * @param bool $useSecretKey
	 */
	public function init($useSecretKey = true)
	{
		$helper = Mage::helper('bonusbox');
		$this
			->resetParameters(true)
			->setUri($helper->getConfig('url') . $this->_resourceName)
			->setHeaders('Accept', $helper->getConfig('accept_header'))
			->setHeaders('Content-Type', self::CONTENT_TYPE)
			->setAuth($helper->getKey($useSecretKey, $this->getStoreId())) // to collect all badges with multiple shops, request has ti be executed with different store context
			->setRawData(null)
		;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getStoreId()
	{
		return $this->_storeId;
	}
	
	/**
	 * @param int $value
	 */
	public function setStoreId($value)
	{
		$this->_storeId = $value;
		return $this;
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
	 * @param mixed $body
	 */
	public function decodeData($body)
	{
		return json_decode($body, true);
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
	 * Request a resource with given method and data.
	 * @link https://github.com/bonusboxme/api_documentation/wiki
	 * @param string $method
	 * @param bool $useSecretKey - Flag for secure/public key
	 * @param mixed $queryData
	 * @param mixed $rawData
	 * @param array $acceptedErrors codes that do not invoke an exception, if an error is provided in the response @TODO move to handleError method for every client
	 */
	public function requestResource($method, $useSecretKey, $queryData = null, $rawData = null, $acceptedErrors = null)
	{
		try {
			$this->init($useSecretKey);
			if ($queryData)
			{
				// @todo if array is provided
				$this->setUri($this->getUri() . '/' . $queryData);
			}
			if ($rawData)
			{
				$this->setRawData($this->encodeData($rawData));
			} 
			$this->setMethod($method);
			
			$response = $this->request();
			if (strpos($response->getStatus(), '2') === 0) # codes in the 2xx range indicate success
			{
				$responseBody = $this->decodeData($response->getBody());
				return $responseBody;
			}
			if (is_array($acceptedErrors) && in_array($response->getStatus(), $acceptedErrors))
			{
				$responseBody = $this->decodeData($response->getBody());
				if ($responseBody['error'])
				{
					return null;
				}
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
			$this->method . ' ' . $this->getUri(),
 			implode("\n", $headers),
 			$this->raw_post_data
		));
	}
}