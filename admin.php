<?php
require 'includes/master.inc.php';
$Auth->requireAdmin('login.php');

if (isset($_GET['edit'])) {
    $user = new User($_GET['edit']);
}

if (isset($_GET['delete'])) {
    $user = new User($_GET['delete']);
    $photos = DBObject::glob('Photo', 'SELECT * FROM biblio_photos WHERE `created_by`="' . $user->id . '" ORDER BY week DESC');

    $path = realpath('upload_photos/');
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

    foreach ($photos as $key => $photo) {
        foreach ($objects as $name => $object) {
            if ($photo->filename == $object->getFilename()) {
                unlink($object->getPathname());
            }
        }
        $photo->delete();
    }
    $user->delete();
    redirect('/admin.php');
}

if (isset($_POST['btnCreateAccount'])) {

    $Error->blank($_POST['username'], 'Username');

    if (!isset($_GET['edit'])) $Error->blank($_POST['password'], 'Password');

    $Error->blank($_POST['level'], 'Level');
    //$Error->email($_POST['email']);


    if ($Error->ok()) {
        if (isset($_GET['edit'])) {
            if ($user->username != $_POST['username'])
                $user = $Auth::changeUsername($user->id, $_POST['username']);

            if (!empty($_POST['password']))
                $user = $Auth::changePassword($user->id, $_POST['password']);

        } else {
            $user = $Auth::createNewUser($_POST['username'], $_POST['password']);
        }

        if (!empty($_FILES['inputLogo']['name'])) {
            $ext = pathinfo($_FILES['inputLogo']['name'], PATHINFO_EXTENSION);

            $user->logo = $Auth->id . '_logo_' . time() . '.' . $ext;

            require_once './includes/ThumbLib.inc.php';

            $thumb = PhpThumbFactory::create($_FILES['inputLogo']['tmp_name']);
            $thumb->resize(200, 150);
            $thumb->save('upload_photos/' . $user->logo);
        }

        $user->level = $_POST['level'];
        $user->bibl_name = $_POST['bibl_name'];
        $user->bibl_name_pl = $_POST['bibl_name_pl'];
        $user->bibl_from = $_POST['bibl_from'];
        $user->country = $_POST['country'];
        $user->position = $_POST['position'];
        $user->link = $_POST['link'];
        $user->local_title = $_POST['localTitle'];
        $user->save();
        //redirect('/admin.php');
    }
} else {
    $username = '';
    //$email     = '';
    $level = 'user';
}

$bibl = DBObject::glob('User', 'SELECT * FROM users WHERE level="user" ORDER BY `position`');

$countries = array(
    'ukr' => 'Україна',
    'ltu' => 'Lietuva',
    'pln' => 'Polska'
);

?>
<?PHP include('inc/header.inc.php'); ?>
    <table class="table table-striped">
        <tr>
            <th>Логін</th>
            <th>Назва</th>
            <th>Звідки</th>
            <th></th>
        </tr>

        <? foreach ($bibl as $bib) : ?>
            <tr>
                <td><?= $bib->username ?></td>
                <td><?= $bib->bibl_name ?><br/><?= $bib->bibl_name_pl ?></td>
                <td><?= $bib->bibl_from ?></td>
                <td><a href="/admin.php?edit=<?= $bib->id ?>">редагувати</a> | <a
                        onclick="return confirm('точно видалити?');"
                        href="/admin.php?delete=<?= $bib->id ?>">видалити</a>
                </td>
            </tr>
        <? endforeach; ?>
    </table>

<?= $Error->alert() ?>
    <form method="post" class="form-signin" enctype="multipart/form-data">
        <h2 class="form-signin-heading"><?= ($user->id ? 'Редагувати' : 'Створити') ?> користувача</h2>
        <div class="row-fluid">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Логін</label>
                    <div class="controls">
                        <input type="text" name="username" id="inputEmail" autocomplete="new-username"
                               value="<?= $user->username ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Пароль</label>
                    <div class="controls">
                        <input type="password" name="password" id="inputEmail" autocomplete="new-password" value="">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputBiblName">Назва бібліотеки</label>
                    <div class="controls">
                        <input type="text" name="bibl_name" id="inputBiblName" value="<?= $user->bibl_name ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputBiblName">Країна</label>
                    <div class="controls">
                        <select name="country">
                            <? foreach ($countries as $key => $country): ?>
                                <option
                                    <?= ($user->country === $key ? 'selected' : '') ?>
                                    value="<?= $key ?>"><?= $country ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputBiblName">Назва бібліотеки (PL)</label>
                    <div class="controls">
                        <input type="text" name="bibl_name_pl" id="inputBiblName" value="<?= $user->bibl_name_pl ?>">
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label" for="inputBiblFrom">Розположення бібліотеки</label>
                    <div class="controls">
                        <input type="text" name="bibl_from" id="inputBiblFrom" value="<?= $user->bibl_from ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPosition">Положення</label>
                    <div class="controls">
                        <input type="text" name="position" id="inputPosition" value="<?= $user->position ?>">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputLogo">Логотип</label>
                    <div class="controls">
                        <input type="file" name="inputLogo" id="inputLogo"/>
                    </div>
                    <? if ($user->logo): ?>
                        <img src="/upload_photos/<?= $user->logo ?>"/>
                    <? endif; ?>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label" for="inputLink">Сайт</label>
                    <div class="controls">
                        <input type="url" name="link" id="inputLink" value="<?= $user->link ?>">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputLocalTitle">Назва проекту</label>
                    <div class="controls">
                        <input type="text" name="localTitle" id="inputLocalTitle" value="<?= $user->local_title ?>">
                    </div>
                </div>

                <select name="level" id="level">
                    <option <?PHP if ($user->level == 'user') echo 'selected="selected"'; ?> value="user">User</option>
                    <option <?PHP if ($user->level == 'admin') echo 'selected="selected"'; ?> value="admin">Admin
                    </option>
                </select>

            </div>
        </div>

        <button class="btn btn-large btn-primary" name="btnCreateAccount" type="submit">
            Зберегти
        </button>
    </form>
<?PHP include('inc/footer.inc.php'); ?>