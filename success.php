<?php 
	include"inc/config.php";
	include"layout/header.php";
	
	// Include language file based on user preference or default
	$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id'; // Default to 'id' if not set
	$translations = include("lang_$lang.php");
	
	if(empty($_SESSION['iam_user'])){
		alert($translations['login_required']);
		redir("login.php");
	}
	
	$user = mysql_fetch_object(mysql_query("SELECT * FROM user WHERE id='$_SESSION[iam_user]'"));
?> 	 
	<div class="col-md-9">
		<div class="alert alert-success"><?php echo $translations['transaction_success']; ?></div>
		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4><?php echo $translations['order_details']; ?></h4>				
				<table class="table table-striped table-hover"> 
					<thead> 
						<tr> 
							<th>#</th> 
							<th><?php echo $translations['product_name']; ?></th> 
							<th><?php echo $translations['unit_price']; ?></th> 
							<th><?php echo $translations['quantity']; ?></th> 
							<th><?php echo $translations['price']; ?></th>   
						</tr> 
					</thead> 
					<tbody> 
					<?php
						$pes = mysql_fetch_array(mysql_query("SELECT * FROM pesanan WHERE user_id='$_SESSION[iam_user]' ORDER BY id DESC LIMIT 1"));
						$q = mysql_query("SELECT * FROM detail_pesanan WHERE pesanan_id='$pes[id]'");
						$total = 0;
						$no = 1;
						while($data = mysql_fetch_object($q)){ ?> 
							<tr> 
								<th scope="row"><?php echo $no++; ?></th> 
								<?php
									$katpro = mysql_query("SELECT * FROM produk WHERE id='$data->produk_id'");
									$p = mysql_fetch_object($katpro);
								?>
								<td><?php echo $p->nama ?></td> 
								<td><?php echo number_format($p->harga, 2, ',', '.')  ?></td>  
								<td><?php echo $data->qty ?></td>
								<?php $t = $data->qty * $p->harga; 
									$total += $t;
								?>
								<td><?php echo number_format($t, 2, ',', '.')  ?></td>  
							</tr>
						<?php } ?>
						<tr>
							<td colspan="4" class="text-center">
								<h5><b><?php echo $translations['total_price']; ?></b></h5>
							</td>
							<td class="text-bold">
								<h5><b><?php echo number_format($total, 2, ',', '.') ?></b></h5>
							</td>
						</tr>
					</tbody> 
				</table>
			</div> 
		</div> 
	</div> 	
<?php include"layout/footer.php"; ?>
