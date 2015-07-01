<?php
spl_autoload_extensions(".php");
spl_autoload_register(
	function ($class_name) {
		/**
		 * TODO: сделать по уму, через spl_autoload()
		 */
		$file_name = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.str_replace("\\", DIRECTORY_SEPARATOR, $class_name).'.php';
		if (file_exists($file_name)) {
			require_once $file_name;
		} elseif (file_exists($file_name = strtolower($file_name))) {
			require_once $file_name;
		}
	}
);
define("_SYS_PATH_", __DIR__);
define("_WEBAPP_", __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'webapp');
