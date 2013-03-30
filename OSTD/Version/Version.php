<?php
namespace OSTD\Version;

class Version{
	protected static $_version = '0.1-20130329';

	public static function getVersion(){
		return self::$_version;
	}
}