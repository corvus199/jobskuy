<?php
include "inc/config.php";
if (!empty($_SESSION['iam_user'])) {
    redir("index.php");
}
include "layout/header.php";

if (!empty($_POST)) {
    extract($_POST);
    $password = md5($password);
    $q = mysql_query("SELECT * FROM `user` WHERE email='$email' AND password='$password'") or die(mysql_error());
    
    // Cek apakah query mengembalikan hasil
    if ($r = mysql_fetch_object($q)) {
        if ($r->status == 'freelance') { // Memeriksa status user
            $_SESSION['iam_freelancer'] = $r->id;
            redir("freelancer/profile.php"); // Redirect ke halaman homepage freelance
        } else {
            $_SESSION['iam_user'] = $r->id;
            redir("index.php"); // Redirect ke halaman homepage biasa jika bukan user freelance
        }
    } else {
        alert("Maaf email dan password anda salah");
    }
}

?>

<h3>Login User</h3>
<hr>
<div class="col-md-6 content-menu" style="margin-top:-20px;">

    <form action="" method="post" enctype="multipart/form-data">
        <label>Email</label><br>
        <input type="email" class="form-control" name="email" placeholder="Email" required="" autofocus="" /><br>
        <label>Password</label><br>
        <input type="password" class="form-control" name="password" placeholder="Password" required="" /> <br>
        <input type="submit" name="form-input" value="Login" class="btn btn-success">
    </form>

</div>
<div class="col-md-12 content-menu">
    Belum Punya Akun ? <a href="register.php">Buat Akun Sekarang !</a>
</div>

<?php include "layout/footer.php"; ?>
