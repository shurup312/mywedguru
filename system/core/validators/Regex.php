<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace system\core\validators;

use Exception;
use Traversable;

class Regex extends AbstractValidator
{
    const INVALID   = 'regexInvalid';
    const NOT_MATCH = 'regexNotMatch';
    const ERROROUS  = 'regexErrorous';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID   => "Неверный тип дпнных. Необходимы строка, целочисленные значения или числа с палавающей точкой",
        self::NOT_MATCH => "Значение не совпадает с шаблоном '%pattern%'",
        self::ERROROUS  => "Произошла внутренняя ошибка при использовании шаблона '%pattern%'",
    );

    /**
     * @var array
     */
    protected $messageVariables = array(
        'pattern' => 'pattern'
    );

    /**
     * Regular expression pattern
     *
     * @var string
     */
    protected $pattern;

	/**
	 * Sets validator options
	 *
	 * @param  string|Traversable $pattern
	 *
	 * @throws \Exception
	 */
    public function __construct($pattern)
    {
        if (is_string($pattern)) {
            $this->setPattern($pattern);
            parent::__construct(array());
            return;
        }

        if (!is_array($pattern)) {
            throw new Exception('Неверный формат параметров для валидации.');
        }

        if (!array_key_exists('pattern', $pattern)) {
            throw new Exception("Утерян параметр 'pattern'.");
        }

        $this->setPattern($pattern['pattern']);
        unset($pattern['pattern']);
        parent::__construct($pattern);
    }

    /**
     * Returns the pattern option
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

	/**
	 * Sets the pattern option
	 *
	 * @param  string $pattern
	 *
	 * @throws \Exception
	 * @return Regex Provides a fluent interface
	 */
    public function setPattern($pattern)
    {
        $this->pattern = (string) $pattern;
        $status        = preg_match($this->pattern, "Test");

        if (false === $status) {
            throw new Exception(
                "Неверно задан шаблон для валидатора '{$this->pattern}'",
                0
            );
        }

        return $this;
    }

    /**
     * Returns true if and only if $value matches against the pattern option
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);

        $status = preg_match($this->pattern, $value);
        if (false === $status) {
            $this->error(self::ERROROUS);
            return false;
        }

        if (!$status) {
            $this->error(self::NOT_MATCH);
            return false;
        }

        return true;
    }
}
