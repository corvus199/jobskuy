<?php
include "inc/config.php";
include "layout/header.php";

// Fungsi untuk memuat file bahasa
function loadLanguage($lang) {
    $lang_file = 'lang_' . $lang . '.php';
    if (file_exists($lang_file)) {
        return include($lang_file);
    } else {
        return include('lang_id.php'); // fallback to default language
    }
}

// Memeriksa apakah ada bahasa yang dipilih oleh pengguna, jika tidak, gunakan default
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id'; // default language
}
$lang = loadLanguage($_SESSION['lang']);

$query = ''; // Inisialisasi variabel $query

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $query = mysql_real_escape_string($query); // Pastikan untuk mengamankan input pengguna

    $sql = "SELECT * FROM produk WHERE nama LIKE '%$query%' OR deskripsi LIKE '%$query%'";
    $result = mysql_query($sql);
} else {
    $result = false; // Jika tidak ada query, set result ke false
}
?>

<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
            <?php if ($query !== '') { ?>
                <h3><?php echo $lang['search_results_for']; ?> "<?php echo htmlspecialchars($query); ?>"</h3>
                <hr>
                <?php
                if ($result && mysql_num_rows($result) > 0) {
                    while ($data = mysql_fetch_array($result)) {
                ?>
                    <div class="col-md-4 content-menu">
                        <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>">
                            <img src="<?php echo $url; ?>uploads/<?php echo $data['gambar'] ?>" width="100%">
                            <h4><?php echo $data['nama'] ?></h4>
                        </a>
                        <p style="font-size:18px"><?php echo $lang['price']; ?>: <?php echo number_format($data['harga'], 2, ',', '.') ?></p>
                        <p>
                            <a href="<?php echo $url; ?>menu.php?id=<?php echo $data['id'] ?>" class="btn btn-success btn-sm" role="button"><?php echo $lang['view_details']; ?></a>
                            <a href="<?php echo $url; ?>keranjang.php?act=beli&produk_id=<?php echo $data['id'] ?>" class="btn btn-info btn-sm" role="button"><?php echo $lang['order']; ?></a>
                        </p>
                    </div>
                <?php
                    }
                } else {
                    echo "<p>" . $lang['no_results_found'] . "</p>";
                }
            } else {
                echo "<p>" . $lang['please_enter_search_keyword'] . "</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include "layout/footer.php"; ?>
