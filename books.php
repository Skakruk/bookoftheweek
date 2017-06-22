<?php
require 'includes/master.inc.php';
$nav = 'books';

if (isset($_GET['action'])) {
    include('edit-book.php');
} else {
    include('view-book.php');
}
