<?php
namespace OSTD\Autoloader;

class Autoloader{
	protected $_baseDir = null;

	public function __construct(){
		$this->_baseDir = getcwd();
	}

	public function loadClass($className){
		$fileName = $this->_baseDir . '/' . str_replace('\\', '/', $className) . '.php';

		if(is_file($fileName)){
			require_once($fileName);
		}
	} 

	public function register(){
		spl_autoload_register(array($this, 'loadClass'));
	}
}
