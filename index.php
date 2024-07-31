<?php
include "inc/config.php";
include "layout/header.php";

// Fungsi untuk memeriksa apakah produk ada di favorit pengguna
function isFavorit($produk_id, $user_id) {
    $result = mysql_query("SELECT * FROM favorit WHERE user_id='$user_id' AND produk_id='$produk_id'");
    return mysql_num_rows($result) > 0;
}

// Load language file
$lang_file = 'lang_' . $_SESSION['lang'] . '.php';
if (file_exists($lang_file)) {
    $lang = include($lang_file);
} else {
    $lang = include('lang_id.php'); // fallback to default language
}
?>

<style>
    .reviews-box {
        background-color: white;
        padding: 10px;
    }

    .review {
        border: 1px gray solid;
        padding: 5px;
        margin-top: 5px;
    }

    .star-icon {
        margin-left: 5px;
        color: orangered;
    }

    .star-icon::before {
        content: '\2605';
        /* kode karakter bintang penuh Unicode */
        font-size: 20px;
        /* sesuaikan ukuran ikon sesuai kebutuhan */
        display: inline-block;
        /* vertical-align: middle; */
    }

    .btn-container {
        display: flex;
        gap: 5px;
    }
</style>

<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
            <h3><?php echo $lang['favorite_services']; ?></h3>

            <?php
            $k = mysql_query("SELECT 
                produk.id,
                produk.nama,
                produk.deskripsi,
                produk.gambar,
                produk.harga,
                produk.kategori_produk_id,
                COALESCE(AVG(review.rating),0) AS avg_rating
            FROM produk 
            LEFT JOIN review ON produk.id = review.produk_id
            GROUP BY produk.id ORDER BY avg_rating DESC LIMIT 3");
            while ($data = mysql_fetch_array($k)) {
                $favorit_class = !empty($_SESSION['iam_user']) && isFavorit($data['id'], $_SESSION['iam_user']) ? 'favorit' : 'not-favorit';
            ?>
                <div class="col-md-4 content-menu">
                    <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>">
                        <img src="<?php echo $url; ?>uploads/<?php echo $data['gambar'] ?>" width="100%">
                        <span><?php echo $lang['rating']; ?>: <span class="star-icon"></span> <?php echo number_format($data['avg_rating'], 1) ?></span>
                        <h4><?php echo $data['nama'] ?></h4>
                    </a>
                    <p style="font-size:18px"><?php echo $lang['price']; ?>: <?php echo number_format($data['harga'], 2, ',', '.') ?></p>
                    <div class="btn-container">
                        <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>" class="btn btn-success btn-sm" role="button"><?php echo $lang['view_details']; ?></a>
                        <a href="<?php echo $url; ?>keranjang.php?act=beli&&produk_id=<?php echo $data['id'] ?>" class="btn btn-info btn-sm" role="button"><?php echo $lang['order']; ?></a>
                        <?php if (!empty($_SESSION['iam_user'])) { ?>
                            <a href="<?php echo $url; ?>tambah_favorit.php?produk_id=<?php echo $data['id'] ?>" class="btn btn-warning btn-sm">
                                <?php echo $lang['favorite']; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            <h3><?php echo $lang['latest_menu']; ?></h3>
            <?php
            $k = mysql_query("SELECT 
                produk.id,
                produk.nama,
                produk.deskripsi,
                produk.gambar,
                produk.harga,
                produk.kategori_produk_id,
                COALESCE(AVG(review.rating),0) AS avg_rating
            FROM produk 
            LEFT JOIN review ON produk.id = review.produk_id
            GROUP BY produk.id ORDER BY avg_rating ASC LIMIT 3");
            while ($data = mysql_fetch_array($k)) {
                $favorit_class = !empty($_SESSION['iam_user']) && isFavorit($data['id'], $_SESSION['iam_user']) ? 'favorit' : 'not-favorit';
            ?>
                <div class="col-md-4 content-menu">
                    <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>">
                        <img src="<?php echo $url; ?>uploads/<?php echo $data['gambar'] ?>" width="100%">
                        <span><?php echo $lang['rating']; ?>: <span class="star-icon"></span> <?php echo number_format($data['avg_rating'], 1) ?></span>
                        <h4><?php echo $data['nama'] ?></h4>
                    </a>
                    <p style="font-size:18px"><?php echo $lang['price']; ?>: <?php echo number_format($data['harga'], 2, ',', '.') ?></p>
                    <div class="btn-container">
                        <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>" class="btn btn-success btn-sm" role="button"><?php echo $lang['view_details']; ?></a>
                        <a href="<?php echo $url; ?>keranjang.php?act=beli&&produk_id=<?php echo $data['id'] ?>" class="btn btn-info btn-sm" role="button"><?php echo $lang['order']; ?></a>
                        <?php if (!empty($_SESSION['iam_user'])) { ?>
                            <a href="<?php echo $url; ?>tambah_favorit.php?produk_id=<?php echo $data['id'] ?>" class="btn btn-warning btn-sm">
                                <?php echo $lang['favorite']; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<?php include "layout/footer.php"; ?>
