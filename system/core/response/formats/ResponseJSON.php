<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 23.06.2015
 * Time: 12:21
 */
namespace system\core\response\formats;

class ResponseJSON implements ResponseFormatInterface
{

	/**
	 * ��������� ����������� �������� � ������� ��� ������ �������
	 *
	 * @param string|array|integer $content
	 *
	 * @return string
	 */
	public static function run($content)
	{
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }
        return (json_encode($content, JSON_UNESCAPED_UNICODE));
	}
}
