<?php
use OSTD\Database\Mysql;
use OSTD\Settings\Settings;

class MysqlTest extends PHPUnit_Framework_TestCase{
	public function testGetTables(){
		$mysql = new Mysql($this->getSettings());

		$mysql->deleteAll();
		$this->assertSame(array(), $mysql->getTables());
		$mysql->loadFile('Tests/Data/Mysql/testGetTables.sql');

		$this->assertSame(array('testtable'), $mysql->getTables());

		$mysql->deleteAll();
		$this->assertSame(array(), $mysql->getTables());
	}

	public function getSettings(){
		return array(
			'database'=>array(
				'driver'=>'mysql',
				'host'=>'127.0.0.1',
				'user'=>'ostdtestuser',
				'pass'=>'ostdtestpassword',
				'database'=>'ostdtestuser',
				'port'=>'8889',
			),
		);
	}
}