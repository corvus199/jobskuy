<?php
include "inc/config.php";
include "layout/header.php";

// Fungsi untuk memeriksa apakah produk ada di favorit pengguna
function isFavorit($produk_id, $user_id) {
    $result = mysql_query("SELECT * FROM favorit WHERE user_id='$user_id' AND produk_id='$produk_id'");
    return mysql_num_rows($result) > 0;
}

// Set default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id'; // default language
}

// Change language if requested
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: ' . $_SERVER['PHP_SELF']); // redirect to current page
    exit;
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
    .star-icon {
        font-size: 1.2em;
        color: orangered;
        display: inline-block;
        margin-right: 2px;
    }
    .star-icon.full::before {
        content: '\2605'; /* Bintang penuh */
    }
    .star-icon.empty::before {
        content: '\2606'; /* Bintang kosong */
    }
    .product-button {
        display: block;
        width: 100%;
        text-align: center;
        margin: 10px 0;
        font-size: 1.5em;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
    }
    .product-button:hover {
        background-color: #0056b3;
    }
</style>

<?php if (!empty($_GET['id'])) { ?>
    <?php
    extract($_GET);
    $k = mysql_query("SELECT produk.*, user.nama as freelancer, user.email, user.telephone, user.skills, user.bahasa
    FROM produk
    LEFT JOIN user ON produk.user_id = user.id
    WHERE produk.id='$id'");
    $data = mysql_fetch_array($k);
    ?>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <h3><?php echo $lang['detail'] . ': ' . $data['nama'] ?></h3>
                <br />
                <div class="col-md-12 content-menu" style="margin-top:-20px;margin-bottom: 10px;">
                    <?php $kat = mysql_fetch_array(mysql_query("SELECT * FROM kategori_produk where id='$data[kategori_produk_id]'")); ?>
                    <small><?php echo $lang['category'] ?> : <a href="<?php echo $url; ?>menu.php?kategori=<?php echo $kat['id'] ?>"><?php echo $kat['nama'] ?></a></small>
                    <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>">
                        <img src="<?php echo $url; ?>uploads/<?php echo $data['gambar'] ?>" width="80%">
                    </a>
                    <br><br>
                    <p><?php echo $data['deskripsi'] ?></p>
                    <p style="font-size:18px"><?php echo $lang['price'] ?> : <?php echo number_format($data['harga'], 2, ',', '.') ?></p>
                    <p>
                        <a href="<?php echo $url; ?>keranjang.php?act=beli&&produk_id=<?php echo $data['id'] ?>" class="btn btn-info" role="button"><?php echo $lang['order'] ?></a>
                        <?php if (!empty($_SESSION['iam_user'])) {
                            $user_id = $_SESSION['iam_user'];
                            $favorit_class = isFavorit($data['id'], $user_id) ? 'favorit' : 'not-favorit';
                            ?>
                            <a href="<?php echo $url; ?>tambah_favorit.php?produk_id=<?php echo $data['id'] ?>" class="btn btn-warning">
                                <span class="star-icon <?php echo $favorit_class; ?>"></span> <?php echo $lang['favorite'] ?>
                            </a>
                        <?php } ?>
                    </p>
                </div>

                <h3><?php echo $lang['about_poster'] ?> :</h3>
                <br />
                <div class="col-md-12 content-menu" style="margin-top:-20px;margin-bottom: 10px;">
                    <h5><?php echo $lang['posted_by'] ?> : <?php echo $data['freelancer'] ?></h5>
                    <h5><?php echo $lang['email'] ?> : <?php echo $data['email'] ?></h5>
                    <h5><?php echo $lang['phone'] ?> : <?php echo $data['telephone'] ?></h5>
                    <h5><?php echo $lang['skills'] ?> : <?php echo $data['skills'] ?></h5>
                    <h5><?php echo $lang['bahasa'] ?> : <?php echo $data['bahasa'] ?></h5>
                </div>

                <div>
                    <h3><?php echo $lang['reviews'] ?></h3>

                    <div class="reviews-box">
                        <?php
                        $k = mysql_query("SELECT 
                        review.id, 
                        review.rating, 
                        review.review,
                        review.created_at,
                        user.nama
                        FROM review 
                        JOIN user ON review.user_id = user.id
                        WHERE produk_id = '$data[id]' ORDER BY id DESC");
                        while ($review = mysql_fetch_array($k)) {
                        ?>
                            <div class="review">
                                <span class="user-name"><?php echo $review['nama'] ?></span>
                                
                                <?php
                                $rating = (int)$review['rating']; // Mengambil rating sebagai integer
                                for ($i = 1; $i <= 5; $i++) {
                                    $class = $i <= $rating ? 'full' : 'empty'; // Tentukan apakah bintang penuh atau kosong
                                    echo "<span class='star-icon $class'></span>";
                                }
                                ?>
                                <?php echo $review['rating'] ?>
                                
                                <p><?php echo $review['review'] ?></p>
                                <p style="font-size: 12px;"><?php echo $review['created_at'] ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } elseif (!empty($_GET['kategori'])) { ?>

    <?php
    extract($_GET);
    $kat = mysql_fetch_array(mysql_query("SELECT * FROM kategori_produk where id='$kategori'"));
    ?>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <h3><?php echo $lang['category'] ?> : <?php echo $kat['nama'] ?></h3>
                <?php
                $k = mysql_query("SELECT * FROM produk where kategori_produk_id='$kategori'");
                while ($data = mysql_fetch_array($k)) {
                    $favorit_class = !empty($_SESSION['iam_user']) && isFavorit($data['id'], $_SESSION['iam_user']) ? 'favorit' : 'not-favorit';
                    ?>
                    <div class="col-md-4 content-menu">
                        <?php if (!in_array($kategori, [6, 7, 8, 9])) { ?>
                            <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>">
                                <img src="<?php echo $url; ?>uploads/<?php echo $data['gambar'] ?>" width="100%">
                                <h4><?php echo $data['nama'] ?></h4>
                            </a>
                        <?php } ?>
                        <?php if (!in_array($kategori, [6, 7, 8, 9])) { ?>
                            <p style="font-size:18px"><?php echo $lang['price'] ?> : <?php echo number_format($data['harga'], 2, ',', '.') ?></p>
                        <?php } ?>
                        <p>
                            <?php if (!in_array($kategori, [6, 7, 8, 9])) { ?>
                                <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>" class="btn btn-success btn-sm" role="button"><?php echo $lang['view_details'] ?></a>
                                <a href="<?php echo $url; ?>keranjang.php?act=beli&&produk_id=<?php echo $data['id'] ?>" class="btn btn-info btn-sm" role="button"><?php echo $lang['order'] ?></a>
                                <?php if (!empty($_SESSION['iam_user'])) { ?>
                                    <a href="<?php echo $url; ?>tambah_favorit.php?produk_id=<?php echo $data['id'] ?>" class="btn btn-warning btn-sm">
                                        <span class="star-icon <?php echo $favorit_class; ?>"></span> <?php echo $lang['favorite'] ?>
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>" class="product-button" role="button"><?php echo $data['nama'] ?></a>
                            <?php } ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php } else { ?>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <h3><?php echo $lang['all_services'] ?></h3>
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
                GROUP BY produk.id");
                while ($data = mysql_fetch_array($k)) {
                    $favorit_class = !empty($_SESSION['iam_user']) && isFavorit($data['id'], $_SESSION['iam_user']) ? 'favorit' : 'not-favorit';
                    ?>
                    <div class="col-md-4 content-menu">
                        <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>">
                            <img src="<?php echo $url; ?>uploads/<?php echo $data['gambar'] ?>" width="100%">
                            <span><?php echo $lang['rating'] ?>: 
                            <?php
                            $avg_rating = (float)$data['avg_rating']; // Mengambil rating rata-rata
                            for ($i = 1; $i <= 5; $i++) {
                                $class = $i <= $avg_rating ? 'full' : 'empty'; // Tentukan apakah bintang penuh atau kosong
                                echo "<span class='star-icon $class'></span>";
                            }
                            ?>
                            <?php echo number_format($avg_rating, 1) ?></span>
                            <h4><?php echo $data['nama'] ?></h4>
                        </a>
                        <p style="font-size:18px"><?php echo $lang['price'] ?> : <?php echo number_format($data['harga'], 2, ',', '.') ?></p>
                        <p>
                            <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>" class="btn btn-success btn-sm" role="button"><?php echo $lang['view_details'] ?></a>
                            <a href="<?php echo $url; ?>keranjang.php?act=beli&&produk_id=<?php echo $data['id'] ?>" class="btn btn-info btn-sm" role="button"><?php echo $lang['order'] ?></a>
                            <?php if (!empty($_SESSION['iam_user'])) { ?>
                                <a href="<?php echo $url; ?>tambah_favorit.php?produk_id=<?php echo $data['id'] ?>" class="btn btn-warning btn-sm">
                                    <span class="star-icon <?php echo $favorit_class; ?>"></span> <?php echo $lang['favorite'] ?>
                                </a>
                            <?php } ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php } ?>
<?php include "layout/footer.php"; ?>
