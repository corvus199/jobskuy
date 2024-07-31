<?php
include "../inc/config.php";

if (!empty($_SESSION['iam_freelacer'])) {
    redir("index.php");
}

include "inc/header.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Post Your Service</h3>
                </div>

                <?php
                if (!empty($_POST)) {
                    extract($_POST);

                    $gambar = md5('Y-m-d H:i:s') . $_FILES['gambar']['name'];
                    extract($_POST);
                    $deskripsi = (!empty($_POST['deskripsi'])) ? $_POST['deskripsi'] : NULL;
                    $q = mysql_query("insert into produk Values(NULL,'$title','$deskripsi','$gambar','$price','$category', $user->id)");
                    if ($q) {
                        $upload = move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/' . $gambar);
                        if ($upload) {
                            alert("Jasa Berhasil di Upload");
                        }
                    }
                }
                ?>


                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" require>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="deskripsi" rows="5" placeholder="Enter description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="text" class="form-control" id="price" name="price" placeholder="Enter price" require>
                        </div>

                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select class="form-control" id="category" name="category">
                                <?php
                                $katpro = mysql_query("select*from kategori_produk");
                                while ($kp = mysql_fetch_array($katpro)) {
                                ?><option value="<?php echo $kp['id']; ?>"><?php echo $kp['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="image">Upload Image:</label>
                            <input type="file" class="form-control-file" id="image" name="gambar" require>
                        </div>

                        <!-- Add more fields as needed -->

                        <button type="submit" class="btn btn-primary btn-block">Post Service</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>