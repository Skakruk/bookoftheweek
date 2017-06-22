<?php

$book = new Photo($_GET['id']);

if (!$book->ok()) redirect('/');

$likedPhotos = $_SESSION['likes'];

include('inc/header.inc.php');
?>
    <div class="row-fluid">
        <div class="span6 offset3">
            <h3 class="text-center">
                <?= weekTitle($book->week) ?>
            </h3>
        </div>
        <div class="span3">
            <? if ($Auth->id == $book->created_by): ?>
                <div class="btn-toolbar text-right">
                    <div class="btn-group">
                        <a class="btn" href="/books/<?= $book->id ?>?action=edit">Редагувати</a>
                        <a class="btn btn-danger" onclick="return confirm('Дійсно видалити?');"
                           href="/books/<?= $book->id ?>?action=delete">Видалити</a>
                    </div>
                </div>
            <? endif; ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span10 offset1">
            <p class="text-center">
                <img
                    src="<?= !empty($book->filename) ? '/upload_photos/.600x600/' . $book->filename : '/img/no_image.jpg' ?>"
                    class="dayphoto img-polaroid">
            </p>

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

            <h3><?= $book->title ?></h3>
            <h4><?= $book->author ?></h4>
            <p><?= nl2br($book->description) ?></p>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div id="disqus_thread"></div>
            <script>
                var disqus_config = function () {
                    this.page.url = "http://bookoftheweek.org.ua/books/<?= $book->id?>";
                    this.page.identifier = "<?= $book->id?>";
                };
                (function () { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');
                    s.src = '//bookoftheweek.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered
                    by Disqus.</a></noscript>

        </div>
    </div>
<?php
include('inc/footer.inc.php');