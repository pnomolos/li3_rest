<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_rest\tests\cases\net\http;

use lithium\action\Request;
use lithium\net\http\Route;
use li3_rest\net\http\Router;
use lithium\action\Response;

class RouterTest extends \lithium\test\Unit {

	protected $_routes = array();

	public function setUp() {
		$this->_routes = Router::get();
		Router::reset();
	}

	public function tearDown() {
		Router::reset();

		foreach ($this->_routes as $route) {
			Router::connect($route);
		}
	}

	public function testResource() {
		Router::resource('Posts');

		$request = new Request(array('url' => '/posts', 'env' => array(
			'REQUEST_METHOD' => 'GET'
		)));
		$params = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'index');
		$this->assertEqual($expected, $params);


		$request = new Request(array('url' => '/posts/1234', 'env' => array(
			'REQUEST_METHOD' => 'GET'
		)));
		$params = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'show', 'id' => '1234');
		$this->assertEqual($expected, $params);
		$this->assertEqual('/posts/1234', Router::match($params));

		$request = new Request(array('url' => '/posts/1234', 'env' => array(
			'REQUEST_METHOD' => 'PUT'
		)));
		$params = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'update', 'id' => '1234');
		$this->assertEqual($expected, $params);

		$request = new Request(array('url' => '/posts', 'env' => array(
			'REQUEST_METHOD' => 'POST'
		)));
		$params = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'create');
		$this->assertEqual($expected, $params);

		$request = new Request(array('url' => '/posts/1234', 'env' => array(
			'REQUEST_METHOD' => 'DELETE'
		)));
		$params = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'delete', 'id' => '1234');
		$this->assertEqual($expected, $params);

	}

	/**
	 * Tests routing based on content type extensions.
	 *
	 * @return void
	 */
	public function testTypeBasedRouting() {
		Router::connect('/{:args}.{:type}', array(), array('continue' => true));
		Router::resource('Posts');

		$request = new Request(array('url' => '/posts.json'));
		$result = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'index', 'type' => 'json');
		$this->assertEqual($expected, $result);


		$request = new Request(array('url' => '/posts/1234.json'));
		$result = Router::process($request)->params;
		$expected = array('controller' => 'posts', 'action' => 'show', 'id' => '1234', 'type' => 'json');
		$this->assertEqual($expected, $result);
	}
}

?>