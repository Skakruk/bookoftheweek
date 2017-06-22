<?php

$Auth->requireUser('login.php');

if ($_GET['action'] === 'delete') {
    $book = new Photo($_GET['id']);
    if (!$book->ok()) redirect('/');
    if ($book->created_by != $Auth->id) redirect('/');

    $path = realpath('upload_photos/');
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($objects as $name => $object) {
        if ($book->filename == $object->getFilename()) {
            unlink($object->getPathname());
        }
    }
    $book->delete();
    redirect('/my-books.php');
    exit();
}

$book = new Photo(isset($_GET['id']) ? $_GET['id'] : null);

if ($_GET['action'] === 'edit') {
    $nav = 'edit-book';

    if (!$book->ok()) redirect('/');

    if ($book->created_by != $Auth->id) redirect('/');
}

if ($_GET['action'] === 'add') {

    $nav = 'add-book';

    $db = Database::getDatabase();

    $photos = DBObject::glob('Photo', "SELECT * FROM biblio_photos WHERE created_by = '{$Auth->id}' ORDER BY week DESC");
    $weeksUsed = array();

    foreach ($photos as $k => $book) {
        $weeksUsed[] = $book->week;
    }

    $book = new Photo();
}

if (isset($_POST['save'])) {
    $book->created_by = $Auth->id;
    $book->week = $_POST['inputWeek'];
    $book->title = $_POST['inputTitle'];
    $book->author = $_POST['author'];
    $book->description = $_POST['inputDescr'];

    if (!empty($_FILES['inputPhoto']['name'])) {
        $ext = pathinfo($_FILES['inputPhoto']['name'], PATHINFO_EXTENSION);
        $book->filename = $Auth->id . '_' . time() . '.' . $ext;
        $book->upload($_FILES["inputPhoto"]["tmp_name"]);
    }

    $id = $book->save();
    redirect('/books/' . $id);
}


$firstMonday = new DateTime('2017-01 monday');
$currentDate = new DateTime();

//($firstMonday->format('W') < $currentDate->format('W') || in_array($firstMonday->format('W'), $weeksUsed) ? 'disabled' : '')
?>
<?php include('inc/header.inc.php'); ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend><?= ($_GET['action'] === 'add' ? 'Додати': 'Редагувати')?> книгу</legend>

            <div class="control-group">
                <label class="control-label" for="inputWeek">Тиждень</label>
                <div class="controls">
                    <select name="inputWeek" id="inputWeek">
                        <? for ($i = 1; $i < 53; $i++): ?>
                            <option
                                <?= ($firstMonday->format('W') === $book->week ? 'selected' : '') ?>
                                value="<?= $firstMonday->format('W') ?>"


                            ><?= $firstMonday->format('Y') ?> <?= get_month_name($firstMonday->format('n') - 1, false) ?>
                                - Тиждень <?= $firstMonday->format('W') ?></option>
                            <?
                            $firstMonday->add(new DateInterval('P7D'));
                        endfor; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPhoto">Обкладинка</label>
                <div class="controls">
                    <input type="file" name="inputPhoto" id="inputPhoto">
                </div>
                <? if ($book->filename): ?>
                    <img src="/upload_photos/.300x225/<?= $book->filename ?>"/>
                <? endif; ?>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputTitle">Назва</label>
                <div class="controls">
                    <input type="text" placeholder="Назва книги" id="inputTitle" name="inputTitle"
                           class="input-xxlarge"
                           value="<?= $book->title ?>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputAuthor">Автор</label>
                <div class="controls">
                    <input type="text" placeholder="Автор" id="inputAuthor" name="author"
                           class="input-xxlarge"
                           value="<?= $book->author ?>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputDescr">Опис</label>
                <div class="controls">
                    <textarea rows="3" placeholder="Короткий опис книги" id="inputDescr"
                              class="input-xxlarge"
                              name="inputDescr"><?= $book->description ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn" name="save">Зберегти</button>
        </fieldset>
    </form>
<?php include('inc/footer.inc.php');