<?php
include "../inc/config.php";

if (!empty($_SESSION['iam_freelacer'])) {
	redir("index.php");
}

include "../layout/header.php";
?>


<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">

				<?php
				if (!empty($_POST)) {
					extract($_POST);

					$password = md5($password);
					$q = mysql_query("INSERT INTO user VALUES(NULL,'$name','$email','$phone','$address','$password','freelance', ' ')");
					if ($q) {
				?>

						<div class="alert alert-success">Register Berhasil.<br>
							<a href="<?php echo $url . "login.php"; ?>">Silahkan Login</a>
						</div>
					<?php } else { ?>
						<div class="alert alert-danger">Terjadi kesalahan dalam pengisian form. Silahkan Coba Lagi</div>
				<?php }
				} ?>

				<div class="card-header">
					<h3 class="text-center">Freelancer Registration</h3>
				</div>
				<div class="card-body">
					<form method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name">Name:</label>
							<input type="text" class="form-control" id="name" name="name" required>
						</div>

						<div class="form-group">
							<label for="email">Email:</label>
							<input type="email" class="form-control" id="email" name="email" required>
						</div>

						<div class="form-group">
							<label for="phone">Phone:</label>
							<input type="tel" class="form-control" id="phone" name="phone" required>
						</div>

						<div class="form-group">
							<label for="address">Address:</label>
							<input type="text" class="form-control" id="address" name="address" required>
						</div>

						<div class="form-group">
							<label for="password">Password:</label>
							<input type="password" class="form-control" id="password" name="password" required>
						</div>

						<button type="submit" class="btn btn-primary btn-block">Register</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>



<? include "../layout/footer.php"; ?>