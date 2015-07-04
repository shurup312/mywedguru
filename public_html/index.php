<?php
define("_ROOT_PATH_", __DIR__);
function convert ($size) {
	$unit = [
		'b',
		'kb',
		'mb',
		'gb',
		'tb',
		'pb'
	];
	return @round($size/pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$unit[$i];
}

function microtime_float () {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
/********* САМО ЯДРО *************/
require "..".DIRECTORY_SEPARATOR."system".DIRECTORY_SEPARATOR."boot.php";
//use em\app;
system\core\App::go()->run();
/********* КОНЕЦ ЯДРА *************/
