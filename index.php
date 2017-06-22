<?php
require 'includes/master.inc.php';
$nav = 'index';

$likedPhotos = $_SESSION['likes'];

$currentDay = new DateTime();

$photos = DBObject::glob('Photo', "SELECT * FROM biblio_photos WHERE week <= '{$currentDay->format('W')}' ORDER BY week DESC");
//$photos = DBObject::glob('Photo', "SELECT * FROM biblio_photos WHERE week <= '02' ORDER BY week DESC");

$dates = array();
$photosByWeekAndUser = array();
$uniqueWeeks = array();
$usersByIds = array();
$allPhotos = array();
$users = array();
$nextWeek = $prevWeek = null;

if (count($photos) > 0) {
    $users = DBObject::glob('User', 'SELECT * FROM users WHERE level="user" ORDER BY `position` ASC');

    foreach ($users as $value) {
        $usersByIds[$value->id] = $value;
    }

    foreach ($photos as $book) {
        $uniqueWeeks[] = $book->week;
        $photosByWeekAndUser[$book->week][$book->created_by] = $book;
        $allPhotos[$book->id] = $book;
    }

    aasort($allPhotos, "week");

    $uniqueWeeks = array_values(array_unique($uniqueWeeks));

    if (isset($_GET['week']) && in_array($_GET['week'], $uniqueWeeks)) {
        $weekDisplayed = $_GET['week'];
    } else {
        $weekDisplayed = $uniqueWeeks[0];
    }


    $nextWeek = array_search($weekDisplayed, $uniqueWeeks) > 0 ? $uniqueWeeks[array_search($weekDisplayed, $uniqueWeeks) - 1] : null;
    $prevWeek = array_search($weekDisplayed, $uniqueWeeks) < (count($uniqueWeeks) - 1) ? $uniqueWeeks[array_search($weekDisplayed, $uniqueWeeks) + 1] : null;

}
?>

<?PHP include('inc/header.inc.php'); ?>
<div class="row-fluid">
    <div class="span2 text-center">
        <? if (!is_null($prevWeek)): ?>
            <a class="arrows" href="/?week=<?= $prevWeek ?>">&larr;</a>
        <? endif; ?>
    </div>
    <div class="span8 text-center">
        <h3>
            <?= weekTitle($weekDisplayed) ?>
        </h3>
    </div>
    <div class="span2 text-center">
        <? if (!is_null($nextWeek)): ?>
            <a class="arrows right" href="/?week=<?= $nextWeek ?>">&rarr;</a>
        <? endif; ?>
    </div>
</div>
<div class="row-fluid visible-desktop">
    <? foreach ($users as $user): ?>
        <div class="span4 text-center biblio-logo">
            <img class="user-flag" src="/img/flags/<?= $user->country ?>.png"/><br/>
            <span class="project-local-title"><?= $user->local_title ?></span><br/>
            <a href="<?= $user->link ?>" target="_blank">
                <img src="/upload_photos/<?= $user->logo ?>"/>
            </a>
        </div>
    <? endforeach; ?>
</div>
<div class="row-fluid">
    <? foreach ($usersByIds as $user) {
        $book = $photosByWeekAndUser[$weekDisplayed][$user->id];
        //unset($allph[$photo->id]);
        ?>
        <? if ($book): ?>
            <div class="span4">
                <h4 class="visible-phone"><?= $user->bibl_name ?></h4>
                <div class="dayimg-wrap">
                    <a class="book-img-link" href="/books/<?= $book->id ?>">
                        <img src="/upload_photos/.300/<?= $book->filename ?>"
                             class="dayphoto img-polaroid">
                    </a>
                </div>
            </div>
        <? else: ?>
            <div class="span4">
                <h4 class="visible-phone"><?= $user->bibl_name ?></h4>
                <p class="dayimg-wrap">
                    <img src="/img/no_image.jpg" class="img-polaroid">
                </p>
            </div>
        <? endif; ?>
    <? } ?>
</div>

<div class="row-fluid">
    <? foreach ($usersByIds as $user) {
        $book = $photosByWeekAndUser[$weekDisplayed][$user->id];
        //unset($allph[$photo->id]);
        ?>
        <div class="span4">
            <? if ($book): ?>
                <h3><?= $book->title ?></h3>
                <h4><?= $book->author ?></h4>

                <div class="like-container">
                    <button class="btn" type="button"
                        <?= (in_array($book->id, $likedPhotos) ? 'disabled' : '') ?>
                            onclick="likePhoto(this, <?= $book->id ?>)">
                        <i class="icon-thumbs-up"></i> Подобається
                    </button>
                    <span class="count-likes badge badge-success"
                          id="likes-for-<?= $book->id ?>"><?= $book->likes ?></span>
                </div>

                <div class="alert alert-success hidden" id="ty-box-<?= $book->id ?>">
                    Дякуємо, Ваш голос враховано!
                </div>

                <a class="comments" href="/books/<?= $book->id ?>">Переглянути повністю та
                    прокоментувати</a>

                <p><?= nl2br($book->description) ?></p>
                <hr class="visible-phone"/>
            <? endif; ?>
        </div>
    <? } ?>
</div>

<div class="row-fluid">
    <div class="span2 text-center">
        <? if (!is_null($prevWeek)): ?>
            <a class="arrows" href="/?week=<?= $prevWeek ?>">&larr;</a>
        <? endif; ?>
    </div>
    <div class="span8 text-center"></div>
    <div class="span2 text-center">
        <? if (!is_null($nextWeek)): ?>
            <a class="arrows right" href="/?week=<?= $nextWeek ?>">&rarr;</a>
        <? endif; ?>
    </div>
</div>
<div class="hidden">
    <? foreach ($allPhotos as $book) {
        $user = $usersByIds[$book->created_by];
        ?>
        <a href="/upload_photos/.600x600/<?= $book->filename ?>" data-ph="<?= $book->id ?>" class="fancybox"
           caption="<?= weekTitle($book->week) ?><br/><b><?= $user->bibl_name ?></b><h3><?= $book->title ?></h3>
                        <h4><?= $book->author ?></h4><br/><?= $book->description ?>"
           rel="gal_<?= $book->created_by ?>">
            <img src="/upload_photos/.300x225/<?= $book->filename ?>"/>
        </a>
    <? } ?>
</div>
<?PHP include('inc/footer.inc.php'); ?>
