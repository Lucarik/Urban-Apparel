<?php
    include('php/session.php');

    //echo $user_check;
    $user = $login_session;
    
    // Get user Id
    $ses_sql = mysqli_query($db,'select `Id` from `Client` where Username = "'.$user.'"');
   
    $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
    $user_id = $row['Id'];
    
    // Get user account level
    $acntlvl = false;
    $query = 'select Username from `Client` where Username = "'.$user.'"';
    $result = $db->query($query);
    $num_results = $result->num_rows;
    if ($num_results > 0) {
        $acntlvl = 1;
    } else {
        $query = 'select Username from `Employee` where Username = "'.$user_check.'"';
        $result = $db->query($query);
        $num_results = $result->num_rows;
        if ($num_results > 0) {
        $acntlvl = 2;
        } else {
            $acntlvl = 3;
        
        }
    }
    json_encode($acntlvl);
    
    
    // Get product from form
    if ((isset($_GET['product_name']))) {
        $search = htmlspecialchars($_GET['product_name']);
        $_SESSION['product_name'] = $search;
    }
    $product_name = $_SESSION['product_name'];

    // Get id
    $ses_sql = mysqli_query($db,'select * from `Stock` where Name = "'.$product_name.'" ');
    
    $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
    
    $product_id = $row['Id'];
    $product_price = $row['Price'];
    $product_size = $row['Size'];
    $product_type = $row['Type'];
    $product_color = $row['Color'];
    $product_stock = $row['Stock_Amount'];
    $product_image = $row['Image'];
    $image_path = 'images/' . $product_image;
?>

<link href="css/styles.css" rel="stylesheet">

<!DOCTYPE html>
<html>
<title>Urban Apparel</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<nav class="navigation_bar">
            <a class="home_link" href="urban_apparel_home.php">URBAN APPAREL</a>
            <a class="nav_link" href="urban_apparel_men_catalog.php">MEN</a>
            <a class="nav_link" href="urban_apparel_women_catalog.php">WOMEN</a>
            <a class="profile_link" href="urban_apparel_profile.php"><?php echo $_SESSION['login_user']; ?></a>
        </nav>
         <?php
        include('php/user_check.php');
        ?>
  
  <header class="w3-container w3-xlarge">
    <p class="w3-left">PRODUCT PAGE</p>
    <p class="w3-right">
    </p>
  </header>

  <div class="w3-container w3-text-grey" id="UA1">
    <p>1 item</p>
  </div>
  <div class="w3-row w3-grayscale">
    <div class="w3-col l3 s6">
      <div class="w3-container">
      <img src="<?php echo $image_path;?>" alt="Product Picture" style="max-height:40%; max-width:20%; margin-left: 50px; border: 1px solid lightgrey;">
      <p class="product-info"><br /><strong> NAME:</strong>
      <?php echo $product_name?></p>
        <p class="product-info"><br /><strong> PRICE:</strong>
        <?php echo $product_price?></p>
        <p class="product-info"><br /><strong> SIZE: </strong>
        <?php echo $product_size?></p>
        <p class="product-info"><br /><strong> TYPE: </strong>
        <?php echo $product_type?></p>
        <p class="product-info"><br /><strong> COLOR: </strong>
        <?php echo $product_color?></p>
        <p class="product-info"><br /><strong> STOCK REMAINING:</strong>
        <?php echo $product_stock?></p>
      </div>
      <div class="w3-container">
      </div>
    </div>    
        <form style="display: none; flex-direction: row; margin-left: 50px; margin-top: 20px;" class="addStockForm" action="" method="post">
            <label for="stockT">Add Stock: </label>
            <input style="margin-left: 5px; width: 100px;" type="number" min=0 oninput="validity.valid||(value='');" name="stockT"  placeholder="Enter amount"/>
            <input type="submit" name="submitStock" value="Submit"/>
        </form>   
        
        <form style="display: none; margin-left: 50px; margin-top: 20px;" class="cartForm" action="" method="post">
            <input type="submit" name="submitCart" value="Add to cart"/>
        </form>
        
        <form style="display: none; margin-left: 50px; margin-top: 20px;" class="deleteForm" action="" method="post">
            <input type="submit" name="deleteProduct" value="Delete Product"/>
        </form>
    </div>
  </div>

<?php
    // Add product to cart
        if ((isset($_POST['submitCart']))) {
            // Check if user has a cart
            $query = 'select * from `Cart` where Client_id = "'.$user_id.'"';
            $result = $db->query($query);
            $num_results = $result->num_rows;
            if ($num_results == 0) {
                // Create new cart if none
                $query = 'INSERT INTO `Cart` (Client_id, Price, Quantity)  VALUES ('.$user_id.', '.$product_price.', 1)';
                $result = $db->query($query);
            } else {
                // Get current price and quantity of current cart 
                $query = 'SELECT Price, Quantity FROM `Cart` where Client_id = '.$user_id.'';
                $result = $db->query($query);
                for ($i=0; $i <$num_results; $i++) {     
                    $row = $result->fetch_assoc();
                    $current_quantity = $row['Quantity'];
                    $current_price = $row['Price'];
                }
                
                $new_quantity = $current_quantity + 1;
                $new_price = $current_price + $product_price;
                
                $query = 'UPDATE `Cart` SET Price = '.$new_price.', Quantity = '.$new_quantity.' WHERE Client_id = '.$user_id.'';
                $result = $db->query($query); 
            }
            // Get cart id
            $query = 'select * from `Cart` where Client_id = '.$user_id.'';
            $result = $db->query($query);
            $num_results = $result->num_rows;
            if ($num_results > 0) {
                for ($i=0; $i <$num_results; $i++) {     
                    $row = $result->fetch_assoc();
                    $cart_id = $row['Id'];
                    $pr = $row['Price'];
                }
                // Insert product into cart
                $result = $db->query('INSERT INTO `Cart_Products` (Cart_Id, Product_Id)  VALUES ('.$cart_id.', '.$product_id.')');
            }
            
            
            

            unset($_POST['submitCart'] );
            //header("Refresh:0");
        }
    ?>
    
    <?php
    // Add stock to product
        if ((isset($_POST['submitStock']))) {
            $amount = $_POST['stockT'];
            $query = 'SELECT Stock_Amount FROM `Stock` WHERE Name = "'.$product_name.'"';
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $stk = $row['Stock_Amount'];
            }
            echo 'Added [' . $amount . '] stock';
            $amount += $stk;
            $result = $db->query('UPDATE `Stock` SET Stock_Amount = '.$amount.' WHERE Name = "'.$product_name.'"');
            
            
        }
    
    ?>
    
    <?php
    // Delete product
        if ((isset($_POST['deleteProduct']))) {

            $result = $db->query('DELETE FROM `Stock` WHERE Name = "'.$product_name.'"');
            echo '<meta http-equiv = "refresh" content = "1; url = urban_apparel_profile.php" />';
        }
    
    ?>
    <script src="js/sidebar.js"></script>
    
    <script type="text/javascript">
        // Display buttons based on user account level
        var acntlvl = <?php echo json_encode($acntlvl);?>;
        if (acntlvl == 1) {
            document.querySelector(".cartForm").style.display = "flex";
        } else if (acntlvl == 2) {
            document.querySelector(".addStockForm").style.display = "flex";
        } else if (acntlvl == 3) {
            // Owner account lvl
            document.querySelector(".deleteForm").style.display = "flex";
        }
    </script>
</body>
</html>
