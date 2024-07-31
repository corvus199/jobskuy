<?php
include "../inc/config.php";

// Cek apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['iam_admin'])) {
    redir("index.php");
}

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    
    // Gunakan mysqli_* untuk koneksi database
    $mysqli = new mysqli("localhost", "root", "", "jobskuy");
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ? AND password = ? AND status = 'admin'");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $r = $result->fetch_object();
        $_SESSION['iam_admin'] = $r->id;
        redir("index.php");
    } else {
        echo "<script>alert('Maaf, email dan password anda salah');</script>";
    }
    
    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login Form</title>

    <link rel='stylesheet' href='<?php echo $url; ?>assets/bootstrap/css/bootstrap.min.css'>
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style_login.css">

</head>

<body>

    <div class="wrapper">
        <form class="form-signin" action="" method="POST">
            <h2 class="form-signin-heading">Silahkan login</h2>
            <input type="email" class="form-control" name="email" placeholder="Email" required="" autofocus="" />
            <input type="password" class="form-control" name="password" placeholder="Password" required="" />
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        </form>
    </div>

</body>

</html>
