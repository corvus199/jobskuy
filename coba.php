<?php
include "inc/config.php";
session_start();

// Set default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default language
}

// Include language file
$langFile = 'lang/' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    $lang = include $langFile;
} else {
    die("Language file not found: " . $langFile);
}

// Database connection using mysql
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_NAME, $link);

// Check connection
if (!$link) {
    die("Connection failed: " . mysql_error());
}

// Delete action
if (!empty($_GET) && $_GET['act'] == 'delete') {
    $id = intval($_GET['id']);
    $q = mysql_query("DELETE FROM pesanan WHERE id = $id");
    if ($q) {
        alert($lang['transaction_deleted']);
        redir("pembayaran.php");
    }
}

// Check if user is logged in
if (empty($_SESSION['iam_user'])) {
    redir("index.php");
}

// Fetch user details
$userId = intval($_SESSION['iam_user']);
$result = mysql_query("SELECT * FROM user WHERE id = $userId");
$user = mysql_fetch_object($result);

// Fetch orders that need payment
$q = mysql_query("SELECT * FROM pesanan WHERE user_id = $userId AND status = 'belum lunas'");
$j = mysql_num_rows($q);

include "layout/header.php";
?>

<div class="col-md-9 content-menu">
    <div class="col-md-12">
        <?php
        if (!empty($_GET) && $_GET['act'] == 'bayar' && !empty($_GET['id'])) {
            $orderId = intval($_GET['id']);
            
            if (!empty($_POST)) {
                $gambar = md5(date('Y-m-d H:i:s')) . $_FILES['gambar']['name'];
                $bayar = $_POST['bayar'];
                $keterangan = $_POST['keterangan'];

                // Insert payment
                $q = mysql_query("INSERT INTO pembayaran (id_pesanan, user_id, bukti_pembayaran, total, status, keterangan, created_at) VALUES ('$orderId', '$userId', '$gambar', '$bayar', 'pending', '$keterangan', NOW())");
                if ($q) {
                    $upload = move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
                    if ($upload) {
                        alert($lang['payment_successful_waiting_confirmation']);
                        redir("pembayaran.php");
                    }
                }
            }

            // Fetch order details
            $result = mysql_query("SELECT * FROM pesanan WHERE id = $orderId");
            $pesanan = mysql_fetch_object($result);

            // Fetch payment details
            $total = 0;
            $result = mysql_query("SELECT * FROM detail_pesanan WHERE pesanan_id = $orderId");
            while ($data = mysql_fetch_object($result)) {
                $resultProd = mysql_query("SELECT * FROM produk WHERE id = $data->produk_id");
                $p = mysql_fetch_object($resultProd);
                $total += $data->qty * $p->harga;
            }

            // Calculate total payment
            $totalPembayaran = 0;
            $result = mysql_query("SELECT * FROM pembayaran WHERE id_pesanan = $orderId AND status = 'verified'");
            while ($d = mysql_fetch_object($result)) {
                $totalPembayaran += $d->total;
            }
        ?>
            <div class="row col-md-6">
                <form action="" method="post" enctype="multipart/form-data">
                    <label><?php echo $lang['total_amount']; ?></label><br>
                    <input type="text" class="form-control" name="total" value="<?php echo 'Rp. ' . number_format($total, 2, ',', '.'); ?>" disabled required><br>
                    <label><?php echo $lang['amount_paid']; ?></label><br>
                    <input type="number" class="form-control" name="bayar" required><br>
                    <label><?php echo $lang['remaining_amount']; ?></label><br>
                    <input type="text" class="form-control" name="kekurangan" value="<?php echo "Rp. " . number_format($total - $totalPembayaran, 2, ",", "."); ?>" disabled required><br>
                    <label><?php echo $lang['payment_proof']; ?></label><br>
                    <input type="file" class="form-control" name="gambar" required><br>
                    <label><?php echo $lang['payment_description']; ?></label><br>
                    <textarea class="form-control" name="keterangan"></textarea><br/>
                    <input type="submit" name="form-input" value="<?php echo $lang['submit']; ?>" class="btn btn-success">
                </form>
            </div>
            <div class="row col-md-12"><hr></div>
        <?php
        }
        ?>
    </div>

    <h3><?php echo $lang['pending_payments']; ?></h3>
    <hr>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo $lang['orderer_name']; ?></th>
                <th><?php echo $lang['order_date']; ?></th>
                <th><?php echo $lang['action']; ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($data = mysql_fetch_object($q)) {
        ?>
            <tr <?php if ($data->read == 0) { echo 'style="background:#cce9f8 !important;"'; } ?>>
                <th scope="row"><?php echo $no++; ?></th>
                <?php
                $resultUser = mysql_query("SELECT * FROM user WHERE id = $data->user_id");
                $user = mysql_fetch_array($resultUser);
                ?>
                <td><?php echo $data->nama; ?></td>
                <td><?php echo substr($data->tanggal_pesan, 0, 10); ?></td>
                <td>
                    <a class="btn btn-sm btn-primary" href="pembayaran.php?act=bayar&id=<?php echo $data->id; ?>"><?php echo $lang['pay']; ?></a>
                    <a class="btn btn-sm btn-danger" href="pembayaran.php?act=delete&id=<?php echo $data->id; ?>"><?php echo $lang['cancel']; ?></a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>

<?php include "layout/footer.php"; ?>
