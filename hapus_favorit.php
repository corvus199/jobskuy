<?php
include "inc/config.php";

if (empty($_SESSION['iam_user'])) {
    echo "Silahkan login dahulu.";
    exit;
}

if (!empty($_GET['produk_id'])) {
    $produk_id = intval($_GET['produk_id']);
    $user_id = intval($_SESSION['iam_user']);

    mysql_query("DELETE FROM favorit WHERE user_id='$user_id' AND produk_id='$produk_id'");

    header("Location: " . $_SERVER['HTTP_REFERER']);
}
?>
