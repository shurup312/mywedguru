<?
use system\core\App;
use webapp\modules\menu\classes\FrontMenuCore;
/**
 * @var string $header
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="ThemeBucket">
	<link rel="shortcut icon" href="/templates/admin/images/favicon.png">

	<title><?= $title ?></title>

	<!--Core CSS -->
	<link href="/templates/admin/bs3/css/bootstrap.min.css" rel="stylesheet">
	<link href="/templates/admin/js/jquery-ui/jquery-ui-1.10.2.custom.css"/>
	<link rel="stylesheet" href="/templates/admin/css/bootstrap-reset.css"/>
	<link rel="stylesheet" href="/templates/admin/font-awesome/css/font-awesome.css"/>
	<!-- Custom styles for this template -->
	<link href="/templates/admin/css/style.css" rel="stylesheet">
	<link href="/templates/admin/css/style-responsive.css" rel="stylesheet"/>
	<?= App::html()->css; ?>

	<!--[if lt IE 9]>
	<script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->


	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
	<link href="/templates/admin/js/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>

</head>

<body>

<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">

	<a href="/adm" class="logo">
		<img src="/templates/admin/images/logo.png" alt="">
	</a>

	<div class="sidebar-toggle-box">
		<div class="fa fa-bars"></div>
	</div>
</div>
<!--logo end-->

<div class="nav notify-row" id="top_menu">
	<!--  notification start -->
	<?php
	/* <ul class="nav top-menu">
		<!-- settings start -->
		<li class="dropdown">
			<a data-toggle="dropdown" class="dropdown-toggle" href="/templates/admin/#">
				<i class="fa fa-tasks"></i>
				<span class="badge bg-success">8</span>
			</a>
			<ul class="dropdown-menu extended tasks-bar">
				<li>
					<p class="">You have 8 pending tasks</p>
				</li>
				<li>
					<a href="#">
						<div class="task-info clearfix">
							<div class="desc pull-left">
								<h5>Target Sell</h5>
								<p>25% , Deadline  12 June’13</p>
							</div>
									<span class="notification-pie-chart pull-right" data-percent="45">
							<span class="percent"></span>
							</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="task-info clearfix">
							<div class="desc pull-left">
								<h5>Product Delivery</h5>
								<p>45% , Deadline  12 June’13</p>
							</div>
									<span class="notification-pie-chart pull-right" data-percent="78">
							<span class="percent"></span>
							</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="task-info clearfix">
							<div class="desc pull-left">
								<h5>Payment collection</h5>
								<p>87% , Deadline  12 June’13</p>
							</div>
									<span class="notification-pie-chart pull-right" data-percent="60">
							<span class="percent"></span>
							</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="task-info clearfix">
							<div class="desc pull-left">
								<h5>Target Sell</h5>
								<p>33% , Deadline  12 June’13</p>
							</div>
									<span class="notification-pie-chart pull-right" data-percent="90">
							<span class="percent"></span>
							</span>
						</div>
					</a>
				</li>

				<li class="external">
					<a href="#">See All Tasks</a>
				</li>
			</ul>
		</li>
		<!-- settings end -->
		<!-- inbox dropdown start-->
		<li id="header_inbox_bar" class="dropdown">
			<a data-toggle="dropdown" class="dropdown-toggle" href="/templates/admin/#">
				<i class="fa fa-envelope-o"></i>
				<span class="badge bg-important">4</span>
			</a>
			<ul class="dropdown-menu extended inbox">
				<li>
					<p class="red">You have 4 Mails</p>
				</li>
				<li>
					<a href="#">
						<span class="photo"><img alt="avatar" src="/templates/admin/images/avatar-mini.jpg"></span>
								<span class="subject">
								<span class="from">Jonathan Smith</span>
								<span class="time">Just now</span>
								</span>
								<span class="message">
									Hello, this is an example msg.
								</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="photo"><img alt="avatar" src="/templates/admin/images/avatar-mini-2.jpg"></span>
								<span class="subject">
								<span class="from">Jane Doe</span>
								<span class="time">2 min ago</span>
								</span>
								<span class="message">
									Nice admin template
								</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="photo"><img alt="avatar" src="/templates/admin/images/avatar-mini-3.jpg"></span>
								<span class="subject">
								<span class="from">Tasi sam</span>
								<span class="time">2 days ago</span>
								</span>
								<span class="message">
									This is an example msg.
								</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="photo"><img alt="avatar" src="/templates/admin/images/avatar-mini.jpg"></span>
								<span class="subject">
								<span class="from">Mr. Perfect</span>
								<span class="time">2 hour ago</span>
								</span>
								<span class="message">
									Hi there, its a test
								</span>
					</a>
				</li>
				<li>
					<a href="#">See all messages</a>
				</li>
			</ul>
		</li>
		<!-- inbox dropdown end -->
		<!-- notification dropdown start-->
		<li id="header_notification_bar" class="dropdown">
			<a data-toggle="dropdown" class="dropdown-toggle" href="/templates/admin/#">

				<i class="fa fa-bell-o"></i>
				<span class="badge bg-warning">3</span>
			</a>
			<ul class="dropdown-menu extended notification">
				<li>
					<p>Notifications</p>
				</li>
				<li>
					<div class="alert alert-info clearfix">
						<span class="alert-icon"><i class="fa fa-bolt"></i></span>
						<div class="noti-info">
							<a href="#"> Server #1 overloaded.</a>
						</div>
					</div>
				</li>
				<li>
					<div class="alert alert-danger clearfix">
						<span class="alert-icon"><i class="fa fa-bolt"></i></span>
						<div class="noti-info">
							<a href="#"> Server #2 overloaded.</a>
						</div>
					</div>
				</li>
				<li>
					<div class="alert alert-success clearfix">
						<span class="alert-icon"><i class="fa fa-bolt"></i></span>
						<div class="noti-info">
							<a href="#"> Server #3 overloaded.</a>
						</div>
					</div>
				</li>

			</ul>
		</li>
		<!-- notification dropdown end -->
	</ul> */
	?>
	<!--  notification end -->
</div>
<div class="top-nav clearfix">
	<!--search & user info start-->
	<ul class="nav pull-right top-menu">
		<li>
			<input type="text" class="form-control search" placeholder=" Что ищем ?">
		</li>
		<!-- user login dropdown start-->
		<li class="dropdown">
			<a data-toggle="dropdown" class="dropdown-toggle" href="/templates/admin/#">
				<img alt="" src="/templates/admin/images/avatar1_small.jpg">
				<span class="username"><?= @$_SESSION['USER']['login']; ?></span>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu extended logout">
				<!--				<li><a href="/#"><i class=" fa fa-suitcase"></i>Профиль</a></li>-->
				<!--				<li><a href="/#"><i class="fa fa-cog"></i> Настройки</a></li>-->
				<li><a href="/auth/logout"><i class="fa fa-key"></i> Выйти</a></li>
			</ul>
		</li>
		<!-- user login dropdown end -->
	</ul>
	<!--search & user info end-->
</div>
</header>
<!--header end-->
<aside>
	<div id="sidebar" class="nav-collapse">
		<!-- sidebar menu start-->
		<div class="leftside-navigation">
			<?
			$menu = new FrontMenuCore();
			$menu->show_menu(1, 'admin_left');
			?>

			<!-- <li class="sub-menu">
				<a href="javascript:;">
					<i class="fa fa-laptop"></i>
					<span>Layouts</span>
				</a>
				<ul class="sub">
					<li><a href="#">Boxed Page</a></li>
					<li><a href="#">Horizontal Menu</a></li>
					<li><a href="#">Language Switch Bar</a>            </li>
				</ul>
			</li> --></div>
		<!-- sidebar menu end-->
	</div>
</aside>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<!-- page start-->

		<div class="row">
			<div class="col-sm-12">
				<section class="panel">
					<header class="panel-heading">
						<?= $header ?>
					</header>
					<div class="panel-body">
						<?= $content ?>

					</div>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->
<!--right sidebar start-->

<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="/templates/admin/js/jquery.js"></script>
<script src="/templates/admin/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
<script src="/templates/admin/bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/templates/admin/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/templates/admin/js/jquery.scrollTo.min.js"></script>
<script src="/templates/admin/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="/templates/admin/js/jquery.nicescroll.js"></script>
<!--Easy Pie Chart-->
<script src="/templates/admin/js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="/templates/admin/js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<!--<script src="/templates/admin/js/flot-chart/jquery.flot.js"></script>-->
<!--<script src="/templates/admin/js/flot-chart/jquery.flot.tooltip.min.js"></script>-->
<!--<script src="/templates/admin/js/flot-chart/jquery.flot.resize.js"></script>-->
<!--<script src="/templates/admin/js/flot-chart/jquery.flot.pie.resize.js"></script>-->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>-->
<script src="/templates/admin/js/fileinput/js/fileinput.min.js" type="text/javascript"></script>
<!-- bootstrap.js below is only needed if you wish to use the feature of viewing details
	  of text file preview via modal dialog -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>-->
<!-- optionally if you need translation for your language then include
	 locale file as mentioned below -->
<script src="/templates/admin/js/fileinput/js/fileinput_locale_ru.js"></script>
<!--common script init for all pages-->
<script src="/templates/admin/js/scripts.js"></script>
<!--custom-->
<script src="/templates/admin/js/app/documents/scripts.js"></script>
<script src="/templates/admin/js/app/pages/scripts.js"></script>

<?

echo App::html()->jsload;

global $time_end;
global $time_start;
$time_end = microtime_float();
$time     = $time_end - $time_start;
?>
<div style="position:fixed;bottom:0px;height:25px;background-color:#777;border-top:1px solid #006;color:#fff;width:100%;padding:3px 0 0 25px;">Загрузка
	страницы  <? global $time;
	echo round($time, 4); ?>  секунд | Затрачено <?= convert(memory_get_usage()); ?> памяти | Затрачено в пике <?= convert(memory_get_peak_usage()); ?>
	памяти;
</div>
</body>
</html>
