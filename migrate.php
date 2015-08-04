<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 24.06.2015
 * Time: 19:55
 */
define("_ROOT_PATH_", __DIR__.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR);
require __DIR__.DIRECTORY_SEPARATOR."system".DIRECTORY_SEPARATOR."boot.php";
use system\core\App;
use system\core\ORM;

App::go();
ORM::rawExecute("CREATE TABLE IF NOT EXISTS `migrate` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NULL DEFAULT NULL,
	`date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)

)ENGINE=InnoDB;");
$migrateList = ORM::forTable('migrate')->findArray();
foreach ($migrateList as $key=>$migrate) {
	$migrateList[$key] = $migrate['name'];
}
$migrateFilesList = glob(dirname(__FILE__).DIRECTORY_SEPARATOR.'migrates'.DIRECTORY_SEPARATOR.'*.php');
foreach ($migrateFilesList as $file) {
	$fileInfo = pathinfo($file);
	if(in_array($fileInfo['filename'], $migrateList)){
		continue;
	}
	echo $fileInfo['filename'].PHP_EOL;
}
if($i==0){
	echo 'No migrates'.PHP_EOL;
	die();
}
echo 'Execute '.$i.' migrates?'.PHP_EOL;

if(fgets(STDIN) == 'y'){
	foreach ($migrateFilesList as $file) {
		$fileInfo = pathinfo($file);
		if(in_array($fileInfo['filename'], $migrateList)){
			continue;
		}
		$queryCode = file_get_contents($file);
		echo "File: ".$file.PHP_EOL;
		echo "Query: ".$queryCode.PHP_EOL;
		ORM::rawExecute($queryCode);
		ORM::rawExecute('INSERT INTO `migrate` (`name`) VALUES ("'.$fileInfo['filename'].'");');
	}
}
