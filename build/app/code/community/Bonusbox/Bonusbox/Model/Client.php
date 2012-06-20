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
 * Basic client to access API
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
class Bonusbox_Bonusbox_Model_Client extends Zend_Http_Client
{
    /**
     * Some constants for initializing client
     */
    const CONTENT_TYPE = 'application/json';
    const METHOD_POST = 'POST',
          METHOD_PUT = 'PUT',
          METHOD_GET = 'GET',
          METHOD_DELETE = 'DELETE';

    /**
     * Name of requested resource -> is set by sub classes
     * 
     * @var string
     */
    protected $_resourceName;

    /**
     * determines store and which config data will be used 
     * 
     * @var int
     * @TODO resolve with config object
     */
    protected $_storeId;

    /**
     * object is created with empty array by magento -> if constructor is invoked
     * with empty array, it requires to contain a valid url  
     */
    public function __construct()
    {
        parent::__construct();
        $adapter = new Zend_Http_Client_Adapter_Curl();
        $this->setAdapter($adapter);
        $adapter->setConfig(
            array(
                'curloptions' => array(
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_SSL_VERIFYHOST => 0
                )
            )
        );
    }

    /**
     * Initialize request
     * 
     * @param bool $useSecretKey Flag indicates using secret key or not
     * 
     * @return Bonusbox_Bonusbox_Model_Client
     */
    public function init($useSecretKey = true)
    {
        $helper = Mage::helper('bonusbox');
        $this->resetParameters(true)
            ->setUri($helper->getConfig('url') . $this->_resourceName)
            ->setHeaders('Accept', $helper->getConfig('accept_header'))
            ->setHeaders('Content-Type', self::CONTENT_TYPE)
            // to collect all badges with multiple shops, request has ti be
            // executed with different store context
            ->setAuth(trim($helper->getKey($useSecretKey, $this->getStoreId())))
            ->setRawData(null);
        
        return $this;
    }

    /**
     * Getting store ID
     * 
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Setting store ID
     * 
     * @param int $value Store ID
     * 
     * @return Bonusbox_Bonusbox_Model_Client
     */
    public function setStoreId($value)
    {
        $this->_storeId = $value;
        return $this;
    }

    /**
     * Encodes data to json format
     * 
     * @param mixed $body Content to encode
     * 
     * @return string
     */
    public function encodeData($body)
    {
        return json_encode($body);
    }

    /**
     * Decodes data from json format
     * 
     * @param mixed $body Content to decode
     * 
     * @return mixed
     */
    public function decodeData($body)
    {
        return json_decode($body, true);
    }

    /**
     * Formats decimal values to int by multiplying and rounding
     * 
     * @param decimal $value     Value to round
     * @param int     $precision Precision to round up to
     * 
     * @return int
     */
    public function encodeDecimal($value, $precision = 2)
    {
        return (int) round($value * pow(10, $precision));
    }

    /**
     * Request a resource with given method and data.
     * 
     * @param string $method         Request method
     * @param bool   $useSecretKey   Flag for secure/public key
     * @param mixed  $queryData      GET query data
     * @param mixed  $rawData        Raw data for request
     * @param array  $acceptedErrors Codes that do not invoke an exception, if an
     *                               error is provided in the response
     * 
     * @return void
     * @link https://github.com/bonusboxme/api_documentation/wiki
     * @TODO move to handleError method for every client
     */
    public function requestResource(
        $method, $useSecretKey, $queryData = null, $rawData = null, $acceptedErrors = null
    )
    {
        try {
            $this->init($useSecretKey);
            if ($queryData) {
                // @todo if array is provided
                $this->setUri($this->getUri() . '/' . $queryData);
            }
            if ($rawData) {
                $this->setRawData($this->encodeData($rawData));
            }
            $this->setMethod($method);

            $response = $this->request();
            // codes in the 2xx range indicate success
            if (strpos($response->getStatus(), '2') === 0) {
                $responseBody = $this->decodeData($response->getBody());
                return $responseBody;
            }
            if (is_array($acceptedErrors) && in_array($response->getStatus(), $acceptedErrors)) {
                $responseBody = $this->decodeData($response->getBody());
                if ($responseBody['error']) {
                    return null;
                }
            }
            require_once 'Bonusbox/Bonusbox/Exception.php';
            throw new Bonusbox_Bonusbox_Exception(
                'Invalid Response' . PHP_EOL . (string) $this . PHP_EOL . PHP_EOL . (string) $response
            );
        } catch (Exception $exc) {
            Mage::helper('bonusbox')->handleError($exc);
        }
    }

    /**
     * Super class does not support __toString
     * 
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        foreach ($this->headers as $header) {
            $headers[] = $header[0] . ': ' . $header[1];
        }
        return implode(
            "\n",
            array(
                $this->method . ' ' . $this->getUri(),
                implode("\n", $headers),
                // @codingStandardsIgnoreStart
                $this->raw_post_data
                // @codingStandardsIgnoreEnd
            )
        );
    }
}