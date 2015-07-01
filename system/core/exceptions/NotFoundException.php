<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 22.05.2015
 * Time: 14:02
 */
namespace system\core\exceptions;

class NotFoundException extends \Exception {

	public function __construct(){
		throw new \Exception('Страница не найдена.', 404);
	}
}
?>
