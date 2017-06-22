<?php

$currentDay = new DateTime();

$archiveBooks = DBObject::glob('Photo', "SELECT * FROM biblio_photos WHERE week <= '{$currentDay->format('W')}' ORDER BY week DESC");
$uniqueArchiveWeeks = array();
if (count($archiveBooks) > 0) {
    foreach ($archiveBooks as $archiveBook) {
        $uniqueArchiveWeeks[] = $archiveBook->week;
    }
    unset($archiveBooks);
    unset($archiveBook);
    $uniqueArchiveWeeks = array_reverse(array_unique($uniqueArchiveWeeks));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Of The Week</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le styles -->
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond" rel="stylesheet">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/jquery.fancybox.css" rel="stylesheet">
    <link href="/css/style.css?v=1.2" rel="stylesheet">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <script type="text/javascript" src="/js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="/js/functions.js"></script>

    <link rel="stylesheet" href="/js/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen"/>
    <script type="text/javascript" src="/js/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

    <link rel="stylesheet" href="/js/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen"/>
    <script type="text/javascript" src="/js/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
</head>

<body class="<?= ($Auth->loggedIn() ? 'logged-in' : '') ?>">
<script>
	(function (i, s, o, g, r, a, m) {
		i['GoogleAnalyticsObject'] = r;
		i[r] = i[r] || function () {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
		a = s.createElement(o),
			m = s.getElementsByTagName(o)[0];
		a.async = 1;
		a.src = g;
		m.parentNode.insertBefore(a, m)
	})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

	ga('create', 'UA-89870148-1', 'auto');
	ga('send', 'pageview');

</script>
<? if ($Auth->loggedIn()): ?>
    <div class="navbar navbar-inverse navbar-fixed-top">

        <div class="navbar-inner">
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" href="/">BookOfTheWeek</a>

                <select class="hidden-desktop nav-archive" onchange="window.location='/?week='+this.value;">
                    <option value="" disabled="disabled" selected="selected">Архів</option>
                    <? foreach ($uniqueWeeks as $week): ?>
                        <option
                            <?= $_GET['week'] == $week ? 'selected="selected"' : '' ?>value="<?= $week ?>">
                            <?= weekTitle($week) ?>
                        </option>
                    <? endforeach; ?>
                </select>

                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li <?= ($nav == 'index') ? 'class="active"' : '' ?>><a href="/">На головну</a></li>
                        <li <?= ($nav == 'my-books') ? 'class="active"' : '' ?>><a href="/my-books.php">Завантажені
                                книги</a>
                        </li>
                        <li <?= ($nav == 'add-book') ? 'class="active"' : '' ?>><a href="/books?action=add">Додати
                                книгу</a></li>

                        <li class="hidden-desktop"><a href="/logout.php">Вийти</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
                <a class="btn btn-small btn-danger pull-right visible-desktop" href="/logout.php">Вийти</a>
            </div>
        </div>
    </div>
<? endif; ?>
<div class="site-title"><a href="/">Book Of The Week 2017</a></div>
<div class="container-fluid main-container">
    <div class="row-fluid">
        <div class="pull-right text-right visible-desktop">
            <p style="margin-top:30px;">
                <select id="arch_sel" onchange="window.location='/?week='+this.value;" style="margin: 0 20px 0;">
                    <option value="" disabled="disabled" selected="selected">Архів</option>
                    <? foreach ($uniqueWeeks as $week): ?>
                        <option
                            <?= $_GET['week'] == $week ? 'selected="selected"' : '' ?>value="<?= $week ?>">
                            <?= weekTitle($week) ?>
                        </option>
                    <? endforeach; ?>
                </select>
                <a href="#" onclick="$('#pdesc').toggleClass('hidden');return false;">Про проект / O projekcie</a>
            </p>
        </div>
    </div>
    <div id="pdesc" class="hidden row-fluid">
        <div class="span8 offset2">
            <p>
                Підліток – це особлива людина. І де б вона не мешкала - в Україні, Польщі чи Литві - вона шукає себе,
                визначає свої цілі у житті.
                «Book of the week» - це ресурс, що містить найкращу літературу для підлітків за рейтингом читачів
                та бібліотекарів
                трьох країн – України, Литви, Польщі. Отже вибирайте, знайомтесь, читайте!
            </p>
            <p>
                The teenager - a special person. And wherever he lived in Ukraine, Poland or Lithuania - he defines his
                goals in life by himself. «Book of the week» - a resource that contains the best books for teens from
                readers and librarians from three countries - Ukraine, Lithuania and Poland. So let's choose and read!
            </p>
            <p>
                Paauglys – tai ypatingas asmuo. Ir kur jis begyventų Ukrainoje, Lenkijoje ar Lietuvoje - jis visada
                ieško ir pats siekia užsibrėžtų tikslų gyvenime. «Book of the week» - tai išteklius, į kurį dedamos
                geriausios Ukrainoje, Lietuvoje ir Lenkijoje skaitytojų ir bibliotekininkų išrinktos knygos paaugliams.
                Taigi rinkitės ir skaitykite!
            </p>
        </div>
    </div>
