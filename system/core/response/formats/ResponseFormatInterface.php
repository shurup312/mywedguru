<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 23.06.2015
 * Time: 12:19
 */
namespace system\core\response\formats;

interface ResponseFormatInterface
{
	/**
	 * ��������� ����������� �������� � ������� ��� ������ �������
	 * @param string|array|integer $content
	 *
	 * @return string
	 */
	public static function run($content);
}
