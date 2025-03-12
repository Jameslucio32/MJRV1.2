<?php
session_start();
include('config/config.php'); 
include('config/checklogin.php');


$mysqli_main = new mysqli($host, $dbuser, $dbpass, 'rposystem'); 

if ($mysqli_main->connect_error) {
    die("Connection failed: " . $mysqli_main->connect_error);
}


$db_inventory = "standalone_inventory_db"; 
$mysqli_inventory = new mysqli($host, $dbuser, $dbpass, $db_inventory);


if ($mysqli_inventory->connect_error) {
    die("Connection failed: " . $mysqli_inventory->connect_error);
}

check_login();

require_once('partials/_head.php');
?>

<body>
 

    <div class="main-content">
    
        <?php require_once('partials/_topnav.php'); ?>
  
        <div style="background-image: url(assets/img/theme/HEADER.png); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>
     
        <div class="container-fluid mt--8">
     
            <div class="row mt-4">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Standalone Inventory </h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Expiration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    $ret_main = "SELECT * FROM rpos_products"; 
                                    $stmt_main = $mysqli_main->prepare($ret_main);
                                    $stmt_main->execute();
                                    $res_main = $stmt_main->get_result();
                                    $counter = 1;

                                    while ($prod = $res_main->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td>
                                                <?php
                                                if ($prod->prod_img) {
                                                    echo "<img src='assets/img/products/$prod->prod_img' height='150' width='125' class='img-thumbnail'>";
                                                } else {
                                                    echo "<img src='assets/img/products/default.jpg' height='120' width='100' class='img-thumbnail'>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_stock); ?> pcs</td>
                                            <td><?php echo htmlspecialchars($prod->prod_expiry_date); ?></td>
                                        </tr>
                                    <?php } ?>

                                    <?php
                                  
                                    $ret_standalone = "SELECT * FROM rpos_products"; 
                                    $stmt_standalone = $mysqli_inventory->prepare($ret_standalone);
                                    $stmt_standalone->execute();
                                    $res_standalone = $stmt_standalone->get_result();

                                    while ($prod = $res_standalone->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td>
                                                <?php
                                                if ($prod->prod_img) {
                                                    echo "<img src='assets/img/products/$prod->prod_img' height='150' width='125' class='img-thumbnail'>";
                                                } else {
                                                    echo "<img src='assets/img/products/default.jpg' height='120' width='100' class='img-thumbnail'>";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($prod->prod_code); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_name); ?></td>
                                            <td><?php echo htmlspecialchars($prod->prod_stock); ?> pcs</td>
                                            <td><?php echo htmlspecialchars($prod->prod_expiry_date); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

          
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>

    <?php require_once('partials/_scripts.php'); ?>

    
    <?php 
    $mysqli_main->close(); 
    $mysqli_inventory->close(); 
    ?>
</body>
</html>