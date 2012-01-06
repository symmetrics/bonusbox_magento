<?php
class Stub_Model_Client extends Bonusbox_Bonusbox_Model_Client
{
	protected $_resource = 'success_pages';
	
	public function request($method)
	{
		switch ($method)
		{
			case 'invalid':
				$response =
'HTTP/1.1 404 Not Found
Server: nginx/1.0.6
Date: Fri, 06 Jan 2012 15:31:20 GMT
Content-Type: text/html; charset=utf-8
Connection: close
Status: 404 Not Found
Content-Length: 728
X-Runtime: 0.050286
X-Rack-Cache: invalidate, pass';
				break;
			case 'valid':
				$response = 
'HTTP/1.1 201 Created
Server: nginx/1.0.6
Date: Fri, 06 Jan 2012 15:27:27 GMT
Content-Type: application/json; charset=utf-8
Connection: close
Status: 201 Created
Location: #<SuccessPage:0xb44d724>
X-UA-Compatible: IE=Edge,chrome=1
ETag: "34f7f0b7717bf5e4f45404d2eec250e1"
Cache-Control: max-age=0, private, must-revalidate
Set-Cookie: _api_session=BAh7BkkiD3Nlc3Npb25faWQGOgZFRkkiJTQyYjgxZGM5MGE0ZDk3YmJiNDc0OThmYmI3NWRmY2MzBjsAVA%3D%3D--2ae331a7d5174bab7e4c6b57c64ab291dc531071; path=/; HttpOnly
X-Runtime: 0.062940
X-Rack-Cache: invalidate, pass

{"success_page":{
"url":"https://fb.bonusbox.me/success_pages/Vso-lxSh59sO9Y2Sa3dDEhkxJfwVAipd-IbPwsR4IsU","token":"Vso-lxSh59sO9Y2Sa3dDEhkxJfwVAipd-IbPwsR4IsU"}
}';
		}
		return Zend_Http_Response::fromString($response); 
	} 
}