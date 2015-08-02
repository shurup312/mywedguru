<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 20.04.15
 * Time: 14:22
 */
use system\core\App;

/**
 * @var Exception $exception
 * @var array     $errorFile
 * @var array     $errorStack
 */
?>
<h3><?= $exception->getMessage(); ?></h3>
<div>Вы можете написать в техническую поддержку <a href="mailto:<?= App::getConfig()['emails']['dev']; ?>"><?= App::getConfig()['emails']['dev']; ?></a> и описать проблему.
	<br/>Наши специалисты в таком случае постараются как можно быстрей устранить причину проблемы.
</div>
<br/>
<div>Заранее спасибо за сотрудничество.</div>

