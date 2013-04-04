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

	public function testApplier(){
		$settings = eval(file_get_contents('OSTD/Settings/defaults.php'));
		$settings = array_merge($settings, $this->getSettings());

		$mysql = new Mysql($settings);
		$mysql->deleteAll();
		$this->assertSame(array(), $mysql->getTables());

		$applier = new \OSTD\Applier($mysql, $settings);
		$applier->setDirectory('Tests/Data/Mysql/testApplier');
		$applier->apply();

		$this->assertSame(array('ostdDatabaseHistory', 'testtable'), $mysql->getTables());

		$applier->apply();
		$this->assertSame(array('ostdDatabaseHistory', 'testtable'), $mysql->getTables());
	}

	public function testApplierWithViews(){
		$settings = eval(file_get_contents('OSTD/Settings/defaults.php'));
		$settings = array_merge($settings, $this->getSettings());

		$mysql = new Mysql($settings);
		$mysql->deleteAll();
		$this->assertSame(array(), $mysql->getViews());

		$applier = new \OSTD\Applier($mysql, $settings);
		$applier->setDirectory('Tests/Data/Mysql/testApplierWithViews');
		$applier->apply();

		$this->assertSame(array('viewtest'), $mysql->getViews());

		$applier->apply();
		$this->assertSame(array('viewtest'), $mysql->getViews());
	}

	public function getSettings(){
		return array(
			'database'=>array(
				'driver'=>'mysql',
				'host'=>'localhost',
				'user'=>'ostdtestuser',
				'pass'=>'ostdtestpassword',
				'database'=>'ostdtestuser',
			),
		);
	}
}