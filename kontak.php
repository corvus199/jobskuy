<?php 
include "inc/config.php";
include "layout/header.php";

// Mengambil teks dari file bahasa
$lang = include 'lang_' . (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id') . '.php';
?> 

<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
        
        <?php 
        if (!empty($_POST)) {
            extract($_POST); 
            
            // Menyimpan data ke database
            $q = mysql_query("INSERT INTO kontak VALUES (NULL, '$nama', '$email', '$subjek', '$pesan')");
            if ($q) {  
        ?>

        <div class="alert alert-success"><?php echo $lang['thank_you']; ?></div>
        <?php 
            } else { 
        ?>
        <div class="alert alert-danger"><?php echo $lang['error_message']; ?></div>
        <?php 
            } 
        } 
        ?>
        
        <h3><?php echo $lang['contact_us']; ?></h3>
        <hr>
        <div class="col-md-8 content-menu" style="margin-top:-20px;">
            <form action="" method="post" enctype="multipart/form-data">
                <label><?php echo $lang['name']; ?></label><br>
                <input type="text" class="form-control" name="nama" required><br>
                <label><?php echo $lang['email']; ?></label><br>
                <input type="email" class="form-control" name="email" required><br>
                <label><?php echo $lang['subject']; ?></label><br>
                <input type="text" class="form-control" name="subjek" required><br>
                <label><?php echo $lang['message']; ?></label><br>
                <textarea class="form-control" name="pesan" required></textarea><br>
                <input type="submit" name="form-input" value="<?php echo $lang['submit']; ?>" class="btn btn-success">
            </form>
        </div>   

        </div>
    </div> 
</div> 

<?php include "layout/footer.php"; ?>
