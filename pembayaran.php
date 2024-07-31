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

// Handle language change
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['id', 'en'])) {
        $_SESSION['lang'] = $lang;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle delete action
if (!empty($_GET) && $_GET['act'] == 'delete') {
    $q = mysql_query("DELETE FROM pesanan WHERE id='$_GET[id]'");
    if ($q) { alert($lang['delete_success']); redir("pembayaran.php"); }
}

// Check user session
if (empty($_SESSION['iam_user'])) {
    redir("index.php");
}

$user = mysql_fetch_object(mysql_query("SELECT * FROM user WHERE id='$_SESSION[iam_user]'"));

include "layout/header.php";

$q = mysql_query("SELECT * FROM pesanan WHERE user_id='$_SESSION[iam_user]' AND status='belum lunas'");
$j = mysql_num_rows($q);
?>

<div class="col-md-9 content-menu">
<div class="col-md-12">
<?php
if (!empty($_GET)) {
    $q1 = mysql_query("SELECT * FROM detail_pesanan WHERE pesanan_id='$_GET[id]'");
    $total = 0;
    $dataPesanan = mysql_fetch_object(mysql_query("SELECT * FROM pesanan WHERE id='$_GET[id]'"));
    while ($data = mysql_fetch_object($q1)) {
        $katpro = mysql_query("SELECT * FROM produk WHERE id='$data->produk_id'");
        $p = mysql_fetch_object($katpro);
        $t = $data->qty * $p->harga;
        $total += $t;
    }

    if ($_GET['act'] == 'bayar' && $_GET['id']) {
        if (!empty($_POST)) {
            $gambar = md5('Y-m-d H:i:s') . $_FILES['gambar']['name'];
            extract($_POST);
            $q = mysql_query("INSERT INTO pembayaran VALUES(NULL, '$_GET[id]', '$_SESSION[iam_user]', '$gambar', '$bayar', 'pending', '$keterangan', NOW())");
            if ($q) {
                $upload = move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
                if ($upload) { alert($lang['payment_success']); redir("pembayaran.php"); }
            }
        }

        extract($_GET);
        $pesanan = mysql_fetch_object(mysql_query("SELECT * FROM pesanan WHERE id='$id'"));
        $qPembayaran = mysql_query("SELECT * FROM pembayaran WHERE id_pesanan='$id' AND status='verified'") or die(mysql_error());
        $totalPembayaran = 0;
        while ($d = mysql_fetch_object($qPembayaran)) {
            $totalPembayaran += $d->total;
        }
        ?>
        <div class="row col-md-6">
        <form action="" method="post" enctype="multipart/form-data">
            <label><?php echo $lang['total_payment']; ?></label><br>
            <input type="text" class="form-control" name="total" value="<?php echo 'Rp. ' . number_format($total, 2, ',', '.'); ?>" disabled required><br>
            <label><?php echo $lang['pay']; ?></label><br>
            <input type="number" class="form-control" name="bayar" required><br>
            <label><?php echo $lang['shortfall']; ?></label><br>
            <input type="text" class="form-control" name="kekurangan" value="<?php echo "Rp. " . number_format($total - $totalPembayaran, 2, ",", "."); ?>" disabled required><br>
            <label><?php echo $lang['payment_proof']; ?></label><br>
            <input type="file" class="form-control" name="gambar" required><br>
            <label><?php echo $lang['description']; ?></label><br>
            <textarea class="form-control" name="keterangan"></textarea><br/>
            <input type="submit" name="form-input" value="<?php echo $lang['send']; ?>" class="btn btn-success">
        </form>
        </div>
        <div class="row col-md-12"><hr></div>
        <?php
    }
}
?>
</div>
    <h3><?php echo $lang['order_pending']; ?></h3>
    <hr>
    <table class="table table-striped table-hove">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo $lang['name']; ?></th>
                <th><?php echo $lang['order_date']; ?></th>
                <th><?php echo $lang['action']; ?></th>
            </tr>
        </thead>
        <tbody>
    <?php while ($data = mysql_fetch_object($q)) { ?>
            <tr <?php if ($data->read == 0) { echo 'style="background:#cce9f8 !important;"'; } ?>>
                <th scope="row"><?php echo $no++; ?></th>
                <?php
                    $katpro = mysql_query("SELECT * FROM user WHERE id='$data->user_id'");
                    $user = mysql_fetch_array($katpro);
                ?>
                <td><?php echo $data->nama ?></td>
                <td><?php echo substr($data->tanggal_pesan, 0, 10) ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="pembayaran.php?act=bayar&id=<?php echo $data->id; ?>"><?php echo $lang['pay_now']; ?></a>
                    <a class="btn btn-sm btn-danger" href="pembayaran.php?act=delete&id=<?php echo $data->id; ?>"><?php echo $lang['cancel']; ?></a>
                </td>
            </tr>
    <?php } ?>
        </tbody>
    </table>
</div>
<?php include "layout/footer.php"; ?>
