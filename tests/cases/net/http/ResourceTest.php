<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_rest\tests\cases\net\http;
use li3_rest\net\http\Resource;

class ResourceTest extends \lithium\test\Unit {

	public function testResourceConnect() {
		$routes = Resource::connect('posts');
		$this->assertEqual(7, count($routes));
	}
}

?>