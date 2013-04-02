<?php

namespace OSTD;

class Applier{
	protected $_directory;

	protected $_database = null;
	protected $_settings = null;

	public function __construct(\OSTD\Database\Database $database, array $settings){
		$this->_database = $database;
		$this->_settings = $settings;
	}

	public function setDirectory($value){
		$this->_directory = $value;
	}

	public function apply(){
		$this->_applyDiffs();
	}

	public function _applyDiffs(){
		$history = $this->_getHistory();

		$files = $this->getFiles($this->_directory . '/Diffs');

		foreach($files as $file){
			if(!in_array($file, $history)){
				$this->_database->loadFile($file);

				$command = 'INSERT INTO ' . $this->_settings['historyTable'] . ' SET filename="'  . $file . '"';
				$this->_database->getConnection()->exec($command);

				if($returnVal === false){
					throw new \Exception('Error while attempting to run query:' . $command);
				}
			}
		}
	}

	public function getFiles($directory){
		$returnVal = array();

		$dir = dir($directory);

		while(false !== ($file = $dir->read())){
			$fullPath = $directory . '/' . $file;

			if($file == '.' || $file == '..'){
			}elseif(is_dir($fullPath)){
				$returnVal = array_merge($returnVal , $this->getFiles($fullPath));
			}else{
				$returnVal[] = $fullPath;
			}
		}

		return $returnVal;
	}

	public function _getHistory(){
		$returnVal = array();

		if(!in_array($this->_settings['historyTable'], $this->_database->getTables())){
			$this->_database->getConnection()->exec('CREATE TABLE ' . $this->_settings['historyTable'] . '(filename char(255))');
		} else {
			$query = $this->_database->getConnection()->prepare('SELECT filename FROM ' . $this->_settings['historyTable']);
			$query->execute();

			foreach($query->fetchAll() as $row){
				$returnVal[] = $row['filename'];
			}
		}

		return $returnVal;
	}
}
