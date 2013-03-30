<?php

use OSTD\Version\Version;

class VersionTest extends PHPUnit_Framework_TestCase{
	public function testGetVersionReturnsNonnull(){
		$this->assertEquals('0.1-20130329', Version::getVersion());
	}
}