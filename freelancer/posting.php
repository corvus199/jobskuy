<?php
include "../inc/config.php";

if (!empty($_SESSION['iam_freelacer'])) {
    redir("index.php");
}

include "inc/header.php";
?>

<div class="container mt-5">
    <?php
    $q = mysql_query("select produk.*, kategori_produk.nama as kat from produk join kategori_produk on produk.kategori_produk_id=kategori_produk.id where user_id=$user->id");
    $j = mysql_num_rows($q);
    ?>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Your Service Listings</h3>
                </div>
                <a class="btn btn-primary btn-block" href="<?php echo $url ?>freelancer/add_poster.php">Buat Postingan</a>
            </div>

            <?php while ($data = mysql_fetch_object($q)) { ?>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="<?php echo $url . 'uploads/' . $data->gambar ?>" class="img-fluid" alt="Service Image" width="100%">
                                </div>
                                <div class="col-md-8">
                                    <h5><?php echo $data->nama ?></h5>
                                    <p><?php echo $data->deskripsi ?></p>
                                    <h5>Rp. <?php echo number_format($data->harga, 2, ',', '.') ?></h5>
                                    <p>Category: <?php echo $data->kat ?></p>
                                    <!-- <p>Skills: HTML, CSS, JavaScript</p> -->
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

</div>

<?php include "inc/footer.php"; ?>