<?php
require 'includes/master.inc.php';
$books = DBObject::glob('Photo', "SELECT * FROM biblio_photos WHERE `created_by`='{$Auth->id}' ORDER BY week DESC");
$i = 0;
?>
<?PHP include('inc/header.inc.php'); ?>
    <div class="row-fluid">
<? foreach ($books as $key => $book) {
    ?>
    <div class="span2">
        <?= weekTitle($book->week) ?>
        <a href="/books/<?= $book->id ?>">
            <img src="/upload_photos/.300x225/<?= $book->filename ?>" class="img-polaroid"/>
        </a>
    </div>
    <? $i++;
    if ($i % 6 == 0) {
        ?>
        </div><div class="row-fluid">
    <? }
} ?>
    </div>
<?PHP include('inc/footer.inc.php'); ?>