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
                    <h3 class="text-center">Freelancer Profile</h3>
                </div>
                <?php
                $user = mysql_fetch_object(mysql_query("SELECT*FROM user where id='$_SESSION[iam_freelancer]'"));
                ?>

                <?php
                if (!empty($_POST)) {
                    extract($_POST);

                    $q = mysql_query("UPDATE user SET nama='$nama', email='$email', telephone='$telephone', alamat='$alamat', skills='$skills', bahasa='$bahasa' WHERE id=$user->id");
                    if ($q) {
                ?>

                        <div class="alert alert-success">Edit Berhasil.<br>
                            <a href="<?php echo $url . "freelancer/profile.php"; ?>">Silahkan Reload</a>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger">Terjadi kesalahan dalam pengisian form. Silahkan Coba Lagi</div>
                <?php }
                } ?>

                <form method="post" enctype="multipart/form-data" class="card-ody">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="nama" id="name" value="<?php echo $user->nama; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $user->email; ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" class="form-control" name="telephone" id="phone" value="<?php echo $user->telephone; ?>">
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" name="alamat" id="address" value="<?php echo $user->alamat; ?>">
                    </div>

                    <div class="form-group">
                        <label for="skills">Skills:</label>
                        <textarea class="form-control" name="skills" id="skills" rows="3"><?php echo $user->skills; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bahasa">Languages we are expert in:</label>
                        <textarea class="form-control" name="bahasa" id="bahasa" rows="3"><?php echo $user->bahasa; ?></textarea>
                    </div>

                    <!-- Add more fields as needed -->

                    <button type="submit" class="btn btn-primary btn-block">Edit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>