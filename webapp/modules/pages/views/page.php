<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 25.05.2015
 * Time: 17:19
 */

/**
 * @var $article
 */

?>
<style type="text/css">
	.pages_content {
		text-align: justify;
	}
</style>
<div class="about_schooll_content p1200" id="content">
	<div class="container">
		<h1><?=$article->title?></h1>

		<div class="about_school_text pages_content">
			<?= $article->content ?>
		</div>
	</div>
</div>
