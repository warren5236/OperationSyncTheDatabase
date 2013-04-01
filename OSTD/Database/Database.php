<?php

use OSTD\Settings\Settings;

namespace OSTD\Database;

class Database {
	protected $_connection = null;

	protected $_settings = null;

	public function __construct(array $settings){
		$this->_settings = $settings;
	}

	public function connect(){
		// connect if we aren't connected yet
		if($this->_connection == null){
			$dbSettings = $this->_settings['database'];
			$connectionString = $dbSettings['driver'] . ':';

			if(isset($dbSettings['port'])){
				$connectionString .='port=' . $dbSettings['port'] . ';';
			}

			$connectionString .= 'host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['database'];
			$this->_connection = new \PDO($connectionString, $dbSettings['user'], $dbSettings['pass']);	
		}
	}

	public function deleteAll(){
		$this->deleteTables();
	}

	public function deleteTables(){
		$this->connect();

		foreach($this->getTables() as $table){
			$this->_connection->exec('DROP TABLE ' . $table);
		}
	}

	public function getTables(){
		$this->connect();

		$returnVal = array();

		$tableQuery = $this->_connection->prepare('SHOW TABLES');
		$tableQuery->execute();
		$tables = $tableQuery->fetchAll();

		foreach($tables as $table){
			$returnVal[] = $table[0];
		}

		return $returnVal;
	}

	public function loadFile($fileName){
		$this->connect();

		$filename = getcwd() . '/' . $fileName;

		if(!is_file($filename)){
			throw new \Exception('Unable to find file: ' . $fileName);
		}

		$fileContents = file_get_contents($fileName);
		$fileContents = str_replace("\n", ' ', $fileContents);
		$fileContents = preg_replace('/\s+/', ' ', $fileContents);

		$commands = explode(';', $fileContents);

		foreach($commands as $command){
			if($command != '' && $command != ' '){
				$this->_connection->exec($command);
			}
		}

		return true;
	}
}