<?php
include "inc/config.php";
include "layout/header.php";

// Memastikan bahasa pengguna diatur
if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id'; // Default ke bahasa Indonesia
}

// Mengambil teks dari file bahasa
$lang_file = 'lang_' . $_SESSION['lang'] . '.php';
if (file_exists($lang_file)) {
    $lang = include $lang_file;
} else {
    // Jika file bahasa tidak ditemukan, gunakan bahasa default ('id')
    $lang = include 'lang_id.php';
}

if (empty($_SESSION['iam_user'])) {
    alert($lang['login_required']);
    redir("login.php");
}

// Memeriksa dan mengatur cart
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = serialize([]);
}
$cart = unserialize($_SESSION['cart']);
if (!is_array($cart)) {
    $cart = [];
}

if (!empty($_POST['form-order'])) {
    extract($_POST);
    $tanggal_pesan = date('Y-m-d H:i:s');
    $val_pesan = date('Y-m-d');
    $val_pesan = new DateTime($val_pesan);
    $difference = $val_pesan->diff($val_pesan);

    if ($difference->days >= 0) {
        $q = mysql_query("INSERT INTO pesanan VALUES (NULL, '$tanggal_pesan', '$_SESSION[iam_user]', '$nama', '$telephone', '0', 'belum lunas')");
        if ($q) {
            $last = mysql_fetch_array(mysql_query("SELECT * FROM pesanan ORDER BY id DESC LIMIT 1"));

            foreach ($cart as $id => $qty) {
                // Mengambil produk
                $product_query = mysql_query("SELECT * FROM produk WHERE id='$id'");
                $product = mysql_fetch_array($product_query);

                if (!empty($product)) {
                    $ins = mysql_query("INSERT INTO detail_pesanan VALUES (NULL, '$product[id]', '$qty', '$last[id]')");
                } else {
                    // Menangani jika produk tidak ditemukan
                    echo "<div class='alert alert-danger'>Produk dengan ID $id tidak ditemukan.</div>";
                }
            }

            unset($_SESSION['cart']);
            redir("success.php");
        }
    } else {
        ?>
        <div class="alert alert-danger"><?php echo $lang['date_error']; ?></div>
        <?php
    }
}
?>

<div class="col-md-9">
    <div class="row">
        <div class="col-md-7">
            <h4><?php echo $lang['confirm_order']; ?></h4>
            <hr>
            <form action="" method="post" enctype="multipart/form-data">
                <label><?php echo $lang['name']; ?></label><br>
                <input type="text" class="form-control" name="nama" required
                value="<?php echo (!empty($_POST['nama'])) ? $_POST['nama'] : $user->nama; ?>"><br>
                <label><?php echo $lang['telephone']; ?></label><br>
                <input type="text" class="form-control" name="telephone" required
                value="<?php echo (!empty($_POST['telephone'])) ? $_POST['telephone'] : $user->telephone; ?>"><br>
                <input type="submit" name="form-order" value="<?php echo $lang['process']; ?>" class="btn btn-success">
            </form>
        </div>
        <div class="col-md-12">
            <hr>
            <h4><?php echo $lang['order_details']; ?></h4>
            <table class="table table-striped" style="width:100%">
                <thead>
                <tr style="background:#c3ebf8;font-weight:bold;">
                    <td style="width:15%"> <?php echo $lang['product']; ?> </td>
                    <td style="width:40%"> <?php echo $lang['details']; ?> </td>
                    <td style="width:10%"> <?php echo $lang['qty']; ?> </td>
                    <td style="width:15%"> <?php echo $lang['total']; ?> </td>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach ($cart as $id => $qty) {
                    $product_query = mysql_query("SELECT * FROM produk WHERE id='$id'");
                    $product = mysql_fetch_array($product_query);
                    
                    if (!empty($product)) {
                        $t = $qty * $product['harga'];
                        $total += $t;
                        ?>
                        <tr class="barang-shop">
                            <td class="CartProductThumb">
                                <div>
                                    <a href="<?php echo $url; ?>menu.php?id=<?php echo $product['id'] ?>">
                                        <img src="<?php echo $url . 'uploads/' . $product['gambar']; ?>" alt="img" width="120px">
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="CartDescription">
                                    <h4><a href="<?php echo $url; ?>menu.php?id=<?php echo $product['id'] ?>"><?php echo $product['nama'] ?></a></h4>
                                    <div class="price"><?php echo "Rp " . number_format($product['harga'], 2, ',', '.') ?></div>
                                </div>
                            </td>
                            <td><?php echo $qty ?> pcs</td>
                            <td class="price"><?php echo number_format($t, 2, ',', '.') ?></td>
                        </tr>
                        <?php
                    } else {
                        // Tampilkan pesan jika produk tidak ditemukan
                        ?>
                        <tr>
                            <td colspan="4"><?php echo $lang['product_not_found']; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr style="background:#c3ebf8;font-weight:bold;">
                    <td colspan="3"><?php echo $lang['total']; ?></td>
                    <td><?php echo number_format($total, 2, ',', '.') ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
</script>

<?php include "layout/footer.php"; ?>
