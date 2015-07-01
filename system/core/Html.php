<?php
namespace system\core;

use system\core\base\Component;
use Exception;

class Html extends Component
{

	public $jsWay;
	public $cssWay;
	public $design = "default";
	public $meta = "";
	public $metaDescription = '';
	public $metaKeywords = '';
	public $content = "";
	public $title = "";
	public $css = "";
	public $jshead = "";
	public $jsload = "";
	public $designFile;
	public $incFileAsContent;

	public function __construct()
	{
		$this->jsWay  = "/templates/".$this->design."/js/";
		$this->cssWay = "/templates/".$this->design."/css/";
	}

	public function setjsWithParams($param, $html5 = false)
	{
		$url = [];
		if (!is_array($param)) {
			$url[] = 'src="'.$param.'"';
		} else {
			foreach ($param as $key => $value) {
				$url[] = $key.'="'.$value.'"';
			}
		}
		$this->jsload .= "<script".((!$html5)?" language=\"JavaScript\" type=\"text/javascript\"":"")." ".implode(' ', $url)."></script>\n";
	}

	/*
	* Добавляем скрипт
	* content - обязательный аргумент
	* если inhead == true, то располагается в заголовке документа, иначе располагается в конце тела документа
	* если inline == true, то content - тело скрипта, иначе content - ссылка на файл скрипта
	* если html5 == true, то скрипт интегрируется в документ в соответствии со спецификацией HTML5 (без указания языка и типа)
	*/
	public function setJs($content = "", $inhead = false, $inline = false, $html5 = false)
	{
		$result = false;
		if (!empty($content)) {
			if ($inhead) {
				if ($inline) {
					$this->jshead .= "<script".((!$html5)?" language=\"JavaScript\" type=\"text/javascript\"":"").">\n"."<!--\n".$content."//-->\n"."</script>\n";
				} else {
					$this->jshead .= "<script".((!$html5)?" language=\"JavaScript\" type=\"text/javascript\"":"")." src=\"".$content."\"></script>\n";
				}
			} else {
				if ($inline) {
					if (file_exists($content)) {
						$content = file_get_contents($content);
					}
					$this->jsload .= "<script".((!$html5)?" language=\"JavaScript\" type=\"text/javascript\"":"").">\n"."<!--\n".$content."//-->\n"."</script>\n";
				} else {
					$this->jsload .= "<script".((!$html5)?" language=\"JavaScript\" type=\"text/javascript\"":"")." src=\"".$content."\"></script>\n";
				}
			}
			$result = true;
		}
		return $result;
	}

	/*
	* Добавляем стиль
	* content - обязательный аргумент
	* если inline == true, то content - тело стиля, иначе content - ссылка на файл стиля
	*/
	public function setCss($content = "", $inline = false)
	{
		$result = false;
		if ($content!="") {
			if ($inline) {
				if (file_exists($content)) {
					$content = file_get_contents($content);
				}
				$this->css .= "<style type=\"text/css\">\n".$content."</style>\n";
			} else {
				$this->css .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$content."\">\n";
			}
			$result = true;
		}
		return $result;
	}

	/*
	* Устанавливаем контент
 	*/
	public function setContent($content = "")
	{
		$result = false;
		if ($content!="") {
			$this->content = $content;
			$result        = true;
		}
		return $result;
	}

	/*
	* Устанавливаем заголовок
 	*/
	public function setTitle($title = "")
	{
		$result = false;
		if ($title!="") {
			$this->title = $title;
			$result      = true;
		}
		return $result;
	}

	/*
	* Добавляем мета тэги
 	*/
	public function setMeta($meta = "")
	{
		$result = false;
		if ($meta!="") {
			$this->meta .= $meta;
			$result = true;
		}
		return $result;
	}
	public function setMetaDescription($description = '')
	{
		$result = false;
		if ($description!="") {
			$this->metaDescription .= $description;
			$result = true;
		}
		return $result;
	}
	public function setMetaKeywords($keywords = '')
	{
		$result = false;
		if ($keywords!="") {
			$this->metaKeywords .= $keywords;
			$result = true;
		}
		return $result;
	}

	/**
	 * Функция рендеринга шаблона!
	 * @deprecated
	 */
	public function render($file, $parameters = [])
	{
		$this->content = $this->renderPartial($file, $parameters);
		ob_start();
		$this->design = trim($this->design, '@');
		$templateFile = _ROOT_PATH_.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$this->design.DIRECTORY_SEPARATOR.$this->design.".php";
		include_once($templateFile);
		$this->designFile = ob_get_contents();
		ob_end_clean();
		return $this->designFile;
	}

	/**
	 * Ренедринг собраного дизайна... по сути это прокладка для удержания готового дизайна
	 * @deprecated
	 */
	public function show($content = false)
	{
		if (!$content) {
			$content = $this->designFile;
		}
		if ($this->fs->includeFile(_SYS_PATH_."/components/blocks/classes/front_blocks.class.php")) {
			$blocks = new \FrontBlocks();
			$content = $blocks->replace_blocks($content);
		}
		echo $content;
	}

	/**
	 * Ошибка 404 для страниц у которых нет гет параметров!
	 * @deprecated
	 */
	function noGet()
	{
		//var_dump($_GET);
		if (!empty($_GET)) {
			$this->tools->error404();
		}
	}

	/**
	 * @param $file
	 * @param $parameters
	 * @deprecated
	 * @return string
	 * @throws Exception
	 */
	public function renderPartial($file, $parameters)
	{
		ob_start();
		func_num_args(1) > 1 && extract((array)func_get_arg(1));
		if (!is_readable($file)) {
			throw new Exception("Шаблон ".$file." не найден");
		}
		require $file;
		return ob_get_clean();
	}
}

?>
