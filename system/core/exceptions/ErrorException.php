<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 22.06.2015
 * Time: 16:22
 */


namespace system\core\exceptions;

use Exception;

class ErrorException extends Exception {
   public function __construct($message, $errorLevel = 0, $errorFile = '', $errorLine = 0) {
      parent::__construct($message, $errorLevel);
      $this->file = $errorFile;
      $this->line = $errorLine;
   }
}
