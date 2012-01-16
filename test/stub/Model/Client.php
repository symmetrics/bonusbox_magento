<?php
class Stub_Model_Client extends Bonusbox_Bonusbox_Model_Client
{
	protected $_resource = 'success_pages';
	
	public function request()
	{
		switch ($this->method)
		{
			case 'invalid':
				$response =
'HTTP/1.1 404 Not Found
Server: nginx/1.0.6
Date: Mon, 16 Jan 2012 14:44:14 GMT
Content-Type: application/json; charset=utf-8
Connection: close
Status: 404 Not Found
X-UA-Compatible: IE=Edge,chrome=1
Cache-Control: no-cache, private
X-Runtime: 0.007974
X-Rack-Cache: miss

{"error":{"message":"invalid coupon","type":"invalid_request_error"}}';
				break;
			case 'valid':
				$response = 
'HTTP/1.1 201 Created
Server: nginx/1.0.6
Date: Mon, 16 Jan 2012 14:47:34 GMT
Content-Type: application/json; charset=utf-8
Connection: close
Status: 201 Created
Location: #<SuccessPage:0x95cfc5c>
X-UA-Compatible: IE=Edge,chrome=1
ETag: "8b32d3de1ff514a3367544ed587a243a"
Cache-Control: max-age=0, private, must-revalidate
Set-Cookie: _api_session=BAh7BkkiD3Nlc3Npb25faWQGOgZFRkkiJTNhOWJlNWY2YjAwMTdhMDUzNzk4M2M0OTk1N2U1NzVlBjsAVA%3D%3D--7cc9c5357da7f031d85e1ee432a0c3b89e4e8716; path=/; HttpOnly
X-Runtime: 0.046943
X-Rack-Cache: invalidate, pass

{"success_page":{"url":"https://fb.bonusbox.me/success_pages/Oje4eyWBD1aCFd1jweYocLSf33-_e3In7efbdtXhwbE","token":"Oje4eyWBD1aCFd1jweYocLSf33-_e3In7efbdtXhwbE"}}';
		}
		return Zend_Http_Response::fromString($response); 
	} 
}