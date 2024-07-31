<?php 
include "inc/config.php";
include "layout/header.php";

// Mengambil teks dari file bahasa
$lang = include 'lang_' . (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id') . '.php';

// Menginisialisasi keranjang
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = '';
}
if (!empty($_GET['produk_id']) && $_GET['act'] == 'beli') {
    $cart = unserialize($_SESSION['cart']);
    if ($cart == '') {
        $cart = [];
    }
    $pid = $_GET['produk_id'];
    $qty = 1;
    
    if (isset($_GET['update_cart'])) {
        if (isset($cart[$pid])) {
            $cart[$pid] = $_GET['qty'];
        }
    } elseif (isset($_GET['delete_cart'])) {
        if (isset($cart[$pid])) {
            unset($cart[$pid]);
        }
    } else {
        if (isset($cart[$pid])) {
            $cart[$pid] += $qty;
        } else {
            $cart[$pid] = $qty;
        }
    }
    $_SESSION['cart'] = serialize($cart);
    redir($url . 'keranjang.php');
}
?> 

<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
            <h2>
                <?php echo $lang['cart']; ?>:
            </h2>
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr style="background:#c3ebf8;font-weight:bold;">
                        <td style="width:15%"> <?php echo $lang['product']; ?> </td>
                        <td style="width:40%"> <?php echo $lang['details']; ?> </td>
                        <td style="width:10%"> <?php echo $lang['qty']; ?> </td>
                        <td style="width:15%"> <?php echo $lang['total']; ?> </td>
                        <td style="width:5%" class="delete">&nbsp;</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    $cart = unserialize($_SESSION['cart']);
                    if ($cart == '') {
                        $cart = [];
                    }
                    foreach ($cart as $id => $qty) {
                        $product = mysql_fetch_array(mysql_query("SELECT * FROM produk WHERE id='$id'"));
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
                                        <h4> 
                                            <a href="<?php echo $url; ?>menu.php?id=<?php echo $product['id'] ?>"><?php echo $product['nama'] ?></a>
                                        </h4>
                                        <div class="price"><?php echo "Rp " . number_format($product['harga'], 2, ',', '.') ?></div>
                                    </div>
                                </td>
                                <td>
                                    <form action="<?php echo $url; ?>keranjang.php" method="GET"> 
                                        <input type="hidden" name="update_cart" value="update">
                                        <input type="hidden" name="act" value="beli">
                                        <input type="hidden" name="produk_id" value="<?= $id ?>">
                                        <input class="form-control" type="number" name="qty" value="<?php echo $qty; ?>" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="price"><?php echo number_format($t, 2, ',', '.') ?></td>
                                <td>
                                    <a href="<?php echo $url; ?>keranjang.php?delete_cart=yes&&act=beli&&produk_id=<?php echo $id; ?>" title="<?php echo $lang['delete']; ?>"> 
                                        <i class="glyphicon glyphicon-trash fa-2x"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } 
                    } ?>
                    <tr style="background:#c3ebf8;font-weight:bold;">
                        <td colspan="3"><?php echo $lang['sub_total']; ?></td>
                        <td><?php echo number_format($total, 2, ',', '.') ?></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>

            <div style="float:right;" class="col-sm-6 col-md-6">
                <h4><b><?php echo $lang['total_cart']; ?></b></h4>
                <table class="table table-bordered">
                    <tr>
                        <td style="background:#fafafa;"><b><?php echo $lang['total']; ?></b></td>
                        <td><b><?php echo "Rp " . number_format($total, 2, ',', '.') ?></b></td>
                    </tr>
                </table>
                <form action="<?php echo $url . 'order.php' ?>" method="POST"> 
                    <input type="hidden" name="okay" value="cart">
                    <button <?php echo ($total == 0) ? 'disabled' : '' ?> type="submit" class="btn btn-primary"><?php echo $lang['finish_shopping']; ?> &raquo;</button>
                </form>
            </div>

        </div> 
    </div> 
</div> 

<?php include "layout/footer.php"; ?>
