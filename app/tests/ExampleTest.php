<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testMail(){
		$data = array("name"=>"Peter","key"=>"1234");
		Mail::send("emails.template",$data, function($message){
			$message->from('yule@trht.com.cn','Coozilla');
    		$message->to('15170965738@163.com', 'John Smith')->subject('Welcome to coozilla!');
		});
	}
}