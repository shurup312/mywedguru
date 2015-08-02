<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 22.05.2015
 * Time: 14:02
 */
namespace system\core\exceptions;

use system\core\App;
use system\core\response\Response;

class NotFoundException extends \Exception {

	public function __construct(){
		App::response()->setStatus(Response::STATUS_404);
		throw new \Exception('Страница не найдена.', 404);
	}
}
?>
