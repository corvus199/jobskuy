<?php
include "inc/config.php";
include "layout/header.php";

// Set default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id'; // Default language to Indonesian
}

// Include language file
$langFile = 'lang_' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    $lang = include $langFile;
} else {
    die("Language file not found: " . $langFile);
}

if (empty($_SESSION['iam_user'])) {
    echo $lang['login_prompt'];
    exit;
}

$user_id = intval($_SESSION['iam_user']);
$favorit_query = mysql_query("SELECT produk.* FROM produk JOIN favorit ON produk.id = favorit.produk_id WHERE favorit.user_id='$user_id'");

// Check if query is successful
if (!$favorit_query) {
    die("Database query failed: " . mysql_error());
}

?>

<div class="col-md-9">
    <h4><?php echo $lang['favorites_list']; ?></h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo $lang['image']; ?></th>
                <th><?php echo $lang['product_name']; ?></th>
                <th><?php echo $lang['price']; ?></th>
                <th><?php echo $lang['action']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysql_fetch_array($favorit_query)) { ?>
                <tr>
                    <td><img src="<?php echo $url . 'uploads/' . htmlspecialchars($row['gambar']); ?>" width="100" alt="<?php echo htmlspecialchars($row['nama']); ?>"></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo "Rp " . number_format($row['harga'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="<?php echo $url ?>hapus_favorit.php?produk_id=<?php echo $row['id']; ?>" class="btn btn-danger"><?php echo $lang['remove']; ?></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include "layout/footer.php"; ?>
