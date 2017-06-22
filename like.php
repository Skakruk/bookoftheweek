<?php
require 'includes/master.inc.php';
$nav = 'photo';
$photo = new Photo($_POST['id']);
if (!$photo->ok()) redirect('/');

$photo->likes += 1;
$photo->save();

$_SESSION['likes'][] = $_POST['id'];

echo json_encode(array(
    'likes' => $photo->likes
));