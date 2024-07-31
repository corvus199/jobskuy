<?php
include "inc/config.php";

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

if (!empty($_GET)) {
    if ($_GET['act'] == 'delete') {
        $q = mysql_query("DELETE FROM pesanan WHERE id='$_GET[id]'");
        if ($q) {
            alert($lang['success']);
            redir("pembayaran.php");
        }
    }
}

if (empty($_SESSION['iam_user'])) {
    redir("index.php");
}
$user = mysql_fetch_object(mysql_query("SELECT * FROM user WHERE id='$_SESSION[iam_user]'"));

include "layout/header.php";

// Query to fetch order details sorted from newest to oldest
$q = mysql_query("SELECT 
    detail_pesanan.id,
    detail_pesanan.produk_id,
    detail_pesanan.qty,
    detail_pesanan.pesanan_id,
    pesanan.tanggal_pesan,
    pesanan.user_id,
    pesanan.status,
    pesanan.read,
    produk.nama,
    produk.harga,
    produk.gambar
FROM detail_pesanan
JOIN pesanan ON detail_pesanan.pesanan_id = pesanan.id
JOIN produk ON detail_pesanan.produk_id = produk.id
WHERE pesanan.user_id = '$_SESSION[iam_user]'
ORDER BY pesanan.tanggal_pesan DESC");
$j = mysql_num_rows($q);

?>

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        font-size: 24px;
    }

    .rating input {
        display: none;
    }

    .rating label {
        cursor: pointer;
        width: 40px;
        height: 40px;
        background-color: #ddd;
        border-radius: 50%;
        margin: 0 4px;
        line-height: 40px;
        text-align: center;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        background-color: #f8d64e;
    }
</style>

<div class="col-md-9 content-menu">
    <div class="col-md-12">
        <?php
        if (!empty($_GET)) {
            $q1 = mysql_query("SELECT 
                detail_pesanan.id,
                detail_pesanan.produk_id,
                detail_pesanan.qty,
                detail_pesanan.pesanan_id,
                pesanan.tanggal_pesan,
                pesanan.user_id,
                pesanan.status,
                pesanan.read,
                produk.nama,
                produk.harga,
                produk.gambar
            FROM detail_pesanan
            JOIN pesanan ON detail_pesanan.pesanan_id = pesanan.id
            JOIN produk ON detail_pesanan.produk_id = produk.id
            WHERE detail_pesanan.id = '$_GET[id]'
            ORDER BY pesanan.tanggal_pesan DESC");

            $total = 0;
            $dataPesanan = mysql_fetch_object(mysql_query("SELECT * FROM pesanan WHERE id='$_GET[id]'"));
            while ($data = mysql_fetch_object($q1)) { ?>
                <?php
                $katpro = mysql_query("SELECT * FROM produk WHERE id='$data->produk_id'");
                $p = mysql_fetch_object($katpro);
                $t = $data->qty * $p->harga;
                $total += $t;

                $produkID = $data->produk_id;
                $nama = $data->nama;
                $gambar = $data->gambar;
                $harga = $data->harga;
                ?>
            <?php } ?>
            <?php
            if ($_GET['act'] == 'review' && $_GET['id']) {
                if (!empty($_POST)) {
                    extract($_POST);
                    $q = mysql_query("INSERT INTO review VALUES (NULL, '$produk_id', '$_SESSION[iam_user]', '$rating', '$review', NOW())");
                    if ($q) {
                        alert($lang['review_thanks']);
                        redir("riwayat_pembelian.php");
                    }
                }
            ?>
                <div class="row col-md-6">
                    <h3><b><?php echo $lang['review']; ?></b></h3>

                    <div>
                        <img src="<?php echo $url; ?>uploads/<?php echo htmlspecialchars($gambar); ?>" width="100%">
                        <h4><?php echo htmlspecialchars($nama); ?></h4>
                        <h4><?php echo "Rp. " . number_format($harga, 2, ',', '.'); ?></h4>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="produk_id" value="<?php echo $produkID ?>">
                        <label><?php echo $lang['rating']; ?></label><br>
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                            <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                        </div>

                        <label><?php echo $lang['review']; ?></label><br>
                        <textarea class="form-control" name="review"></textarea><br />
                        <input type="submit" name="form-input" value="<?php echo $lang['submit']; ?>" class="btn btn-success">
                    </form>
                </div>
                <div class="row col-md-12">
                    <hr>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <h3><?php echo $lang['order_history']; ?></h3>
    <hr>
    <table class="table table-striped table-hove">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo $lang['product_name']; ?></th>
                <th><?php echo $lang['date_ordered']; ?></th>
                <th><?php echo $lang['quantity']; ?></th>
                <th><?php echo $lang['subtotal']; ?></th>
                <th><?php echo $lang['action']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($data = mysql_fetch_object($q)) { ?>
                <tr <?php if ($data->read == 0) {
                        echo 'style="background:#cce9f8 !important;"';
                    } ?>>
                    <th scope="row"><?php echo $no++; ?></th>
                    <?php
                    $katpro = mysql_query("SELECT * FROM user WHERE id='$data->user_id'");
                    $user = mysql_fetch_array($katpro);
                    ?>
                    <td><?php echo htmlspecialchars($data->nama); ?></td>
                    <td><?php echo substr($data->tanggal_pesan, 0, 10); ?></td>
                    <td><?php echo htmlspecialchars($data->qty); ?></td>
                    <td><?php echo number_format($data->harga * $data->qty, 2, ',', '.'); ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="riwayat_pembelian.php?act=review&id=<?php echo $data->id; ?>"><?php echo $lang['review']; ?></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>
<?php include "layout/footer.php"; ?>
