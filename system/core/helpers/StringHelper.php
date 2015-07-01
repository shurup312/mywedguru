<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 27.03.2015
 * Time: 14:10
 */
namespace system\core\helpers;

use DateTime;
use system\core\App;

class StringHelper
{

	public static function randomString($length, $charset = false)
	{
		$result = '';
		if (!$charset) {
			$charset = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
		}
		$charLen = strlen($charset);
		for ($i = 0;$i < $length;$i++) {
			$result .= $charset{mt_rand(0, $charLen - 1)};
		}
		return $result;
	}

	public static function makeAlias($string)
	{
		$charsDenied = [
			'/'=>'',
			'\\'=>'',
			'#'=>'',
			'!'=>'',
			'@'=>'',
			'$'=>'',
			'%'=>'',
			'^'=>'',
			'&'=>'',
			'*'=>'',
			'('=>'',
			')'=>'',
			'+'=>'',
			'='=>'',
			'['=>'',
			']'=>'',
			'.'=>'',
			','=>'',
			'?'=>'',
			'>'=>'',
			'<'=>'',
			'|'=>'',
			'`'=>'',
			'~'=>'',
		];
		$url         = strtr($string, $charsDenied);
		$url         = self::cyrToLat($url);
		return $url;
	}

	public static function cyrToLat($string)
	{
		$charset = [
			'Є'  => 'YE',
			'І'  => 'I',
			'Ѓ'  => 'G',
			'і'  => 'i',
			'№'  => '-',
			'є'  => 'ye',
			'ѓ'  => 'g',
			'А'  => 'A',
			'Б'  => 'B',
			'В'  => 'V',
			'Г'  => 'G',
			'Д'  => 'D',
			'Е'  => 'E',
			'Ё'  => 'YO',
			'Ж'  => 'ZH',
			'З'  => 'Z',
			'И'  => 'I',
			'Й'  => 'J',
			'К'  => 'K',
			'Л'  => 'L',
			'М'  => 'M',
			'Н'  => 'N',
			'О'  => 'O',
			'П'  => 'P',
			'Р'  => 'R',
			'С'  => 'S',
			'Т'  => 'T',
			'У'  => 'U',
			'Ф'  => 'F',
			'Х'  => 'X',
			'Ц'  => 'C',
			'Ч'  => 'CH',
			'Ш'  => 'SH',
			'Щ'  => 'SHH',
			'Ъ'  => '',
			'Ы'  => 'Y',
			'Ь'  => '',
			'Э'  => 'E',
			'Ю'  => 'YU',
			'Я'  => 'YA',
			'а'  => 'a',
			'б'  => 'b',
			'в'  => 'v',
			'г'  => 'g',
			'д'  => 'd',
			'е'  => 'e',
			'ё'  => 'yo',
			'ж'  => 'zh',
			'з'  => 'z',
			'и'  => 'i',
			'й'  => 'j',
			'к'  => 'k',
			'л'  => 'l',
			'м'  => 'm',
			'н'  => 'n',
			'о'  => 'o',
			'п'  => 'p',
			'р'  => 'r',
			'с'  => 's',
			'т'  => 't',
			'у'  => 'u',
			'ф'  => 'f',
			'х'  => 'x',
			'ц'  => 'c',
			'ч'  => 'ch',
			'ш'  => 'sh',
			'щ'  => 'shh',
			'ъ'  => '',
			'ы'  => 'y',
			'ь'  => '',
			'э'  => 'e',
			'ю'  => 'yu',
			'я'  => 'ya',
			' '  => '_',
			'—'  => '_',
			','  => '_',
			'!'  => '_',
			'@'  => '_',
			'#'  => '-',
			'$'  => '',
			'%'  => '',
			'^'  => '',
			'&'  => '',
			'*'  => '',
			'('  => '',
			')'  => '',
			'+'  => '',
			'='  => '',
			';'  => '',
			':'  => '',
			'\'' => '',
			'"'  => '',
			'~'  => '',
			'`'  => '',
			'?'  => '',
			'/'  => '',
			'\\' => '',
			'['  => '',
			']'  => '',
			'{'  => '',
			'}'  => '',
			'|'  => '',
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'0'  => '0',
		];
		return strtr($string, $charset);
	}

	public static function formattedDate(DateTime $date)
	{
		return $date->format(App::getConfig()['dateFormat']);
	}

	public static function encode($string)
	{
		return htmlentities($string);
	}
}

?>
