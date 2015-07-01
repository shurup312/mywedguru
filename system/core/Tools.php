<?php
namespace system\core;

class Tools
{
	/*
	* Функция защищает строку! Путем кодирования в сущности и добавления слэшей 
	*/
	public static function strCheck($str)
	{
		return ((!empty($str) && !is_array($str)) ? trim(((!get_magic_quotes_gpc()) ? addslashes(htmlspecialchars($str, ENT_QUOTES, 'utf-8')) : htmlspecialchars($str, ENT_QUOTES, 'utf-8'))) : "");
	}
	/*
	* Фунция декодирования строки из сущностей 
	*/
	public static function strCheckDecode($str)
	{
		return htmlspecialchars_decode(stripslashes($str));
	}
	/*
	* Фунция генерации пароля 
	*/
	public static function passGenerate($qty=8, $simple = false)
	{
		$result = "";

		if($simple == false)
		{
			$l = "`1234567890-=\\qwertyuiop[]asdfghjkl;'zxcvbnm,./~!@#$%^&*()_+|QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>?ёйцукенгшщзхъфывапролджэячсмитьбюЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ ";
		}else{
			$l = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		}
		$q = strlen($l);
		for ($i=0; $i<$qty; $i++)
		{
			$result .= $l{mt_rand(0, $q-1)};
		}

		return $result;
	}
	/*
	* Фунция отправки почтового сообщения 
	*/
	public static function sendMail($address, $title, $message)
	{
		$ttl = "=?utf-8?B?".base64_encode($title)."?=";

		$hdr	= "Content-Type: text/html; charset=utf-8\r\n"
			. "From: ".$_SERVER["SERVER_NAME"]." <info@".$_SERVER["SERVER_NAME"].">\r\n"
			. "Date: ".date("r")."\r\n"
			. "Reply-To: ".$_SERVER["SERVER_NAME"]." <info@".$_SERVER["SERVER_NAME"].">\r\n"
			. "X-Mailer: PHP/".phpversion()."\r\n";

		return mail($address, $ttl, $message, $hdr);
	}
	/*
	* cyrToLat - функция преобразует строку на русском в строку на латинице транслит)
	*/
	public static function cyrToLat($str)
	{
		$tr = array(
			"Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I",
			"і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
			"ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
			"Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
			"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
			"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
			"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
			"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"'","Ы"=>"YI","Ь"=>"",
			"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
			"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
			"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
			"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
			"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", " "=>"_", "'"=>"", "\""=>"", "\\\\"=>"", "."=>"_"
		);	
		$in = strtolower(substr(strtr(trim($str), $tr), 0, 220));
		$in = preg_replace("/[-`~!#$%^&*()=+\\\\|\\[\\]{};:\"',<>?]+/", "", $in);
		if (substr($in, -1) == "/" and strlen($in)>1) $in = substr($in, 0, -1);
		//ФИНАЛЬНАЯ ПРОВЕКА
		$TR2 = array("___"=>"_", "__"=>"_", "____"=>"_");

		return strtr($in, $TR2);
	}
	/*
	* Функия преобразует строку на русском в английси текст через службы гугл перевода!
	*/
	public static function RusToEng($title)
	{
		$result = $title;

		$url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=".urlencode($title)."&langpair=ru%7Cen";
		if (($translate = file_get_contents($url)) !== false)
		{
			$json = json_decode($translate, true);
			if ($json["responseStatus"] == 200)
			{
				$result = strtolower(trim(preg_replace(array("~\W~", "~-+~"), array("-", "-"), stripslashes(htmlspecialchars_decode($json["responseData"]["translatedText"]))), "-"));
			}
		}
	   
		return $result;
	}
	/*
	* Функция ошибка! 404 
	*/
	public function error404()
	{
		 header("HTTP/1.1 404 Not Found");
		 die("NOT FOUND");
	}
    /*
     * Функция конвертирует арабское число в римское
     */
    function ArabicToRim($value)
    {
        if($value<0) return "";
        if(!$value) return "0";
        $thousands=(int)($value/1000);
        $value-=$thousands*1000;
        $result=str_repeat("M",$thousands);
        $table=array(
            900=>"CM",500=>"D",400=>"CD",100=>"C",
            90=>"XC",50=>"L",40=>"XL",10=>"X",
            9=>"IX",5=>"V",4=>"IV",1=>"I");
        while($value) {
            foreach($table as $part=>$fragment) if($part<=$value) break;
            $amount=(int)($value/$part);
            $value-=$part*$amount;
            $result.=str_repeat($fragment,$amount);
        }
        return $result;
    }
	/*
	* Функци для печати перменной
	*/
	public function printValue($value)
	{
		if (!empty($value))
		{
			echo "<pre>";
			if (is_array($value) || is_object($value))
			{
				print_r($value);
			}
			else
			{
				echo $value;
			}
			echo "</pre>\n";
		}
	}
    /*
     * Получение и обработку пост даных! на лету
     * МАССИВЫ ПОКА ИГНОРИРУЮТСЯ!!!!
     */
    function getPost ()
    {
//var_dump($_POST);
        if (!empty($_POST))
        {
            foreach ($_POST as $k=>$v)
            {
                //получаем данные, и экранируем их!
                if (is_array($v))
                {
                    $data[$this->strCheck($k)]=$v;

                } else {

                    $data[$this->strCheck($k)]=$this->strCheck($v);
                }
            }

        }
        if (!empty($data))
            return $data;
    }
    
    
    /*
     * Обрезаем строку до нужной длинны и ставим к обрезку троеточие
     * $str - входящая строка. Если строка меньше обрезаемых символов - возвращаем всю строку
     * $count - кол-во символов, на которое нужно обрезать строку
     * $begin - номер символа, с которого нужно обрезать строку
    */
    function cropStr($str, $count, $begin = 0)
    {
        if (!empty($str))
        {
            $t_str = $this->strCheck($str);
            $cur_str_count = strlen($t_str);
            if ($cur_str_count > $count)
            {
                return mb_substr($t_str, $begin, $count, 'UTF-8') . '...';
            }
            else
            {
                return $t_str;
            }
        }
        else
        {
            return '';
        }
    }
}

?>
