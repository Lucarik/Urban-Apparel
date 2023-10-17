<?php
    include('php/session.php');

    //echo $user_check;
    $user = $login_session;
    
    // Get user Id
    $ses_sql = mysqli_query($db,'select `Id` from `Client` where Username = "'.$user.'"');
   
    $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
    $user_id = $row['Id'];
    
    // Check if user has a cart
    $hascart = false;
    $query = 'select * from `Cart` where Client_id = "'.$user_id.'"';
    $result = $db->query($query);
    $num_results = $result->num_rows;
    if ($num_results > 0) {
        $hascart = true;
        for ($i=0; $i <$num_results; $i++) {     
            $row = $result->fetch_assoc();
            $cart_id = $row['Id'];
            $quantity = $row['Quantity'];
            $price = $row['Price'];
        }
    }
?>
<!DOCTYPE html>
<html>
<title>Urban Apparel</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/styles.css" rel="stylesheet">
<link href="css/styles_user.css" rel="stylesheet">
<body>
    <nav class="navigation_bar">
            <a class="home_link" href="urban_apparel_home.php">URBAN APPAREL</a>
            <a class="nav_link" href="urban_apparel_men_catalog.php">MEN</a>
            <a class="nav_link" href="urban_apparel_women_catalog.php">WOMEN</a>
            <a class="profile_link" href="urban_apparel_profile.php"><?php echo $_SESSION['login_user']; ?></a>
        </nav>
         <?php
        include('php/user_check.php');
        ?>
        <h1 class="pTitle" style="margin-left: 50px; margin-top: 40px;">Current Cart</h1>
    <div class="product-body">
        <?php
            // If user has a cart display items
            if ($hascart) {
                $arr = array();
                
                $query = 'SELECT c.Id, Name, Price FROM `Cart_Products` AS c JOIN `Stock` AS s ON s.Id = c.Product_id WHERE `Cart_id` = '.$cart_id.'';
                $result = $db->query($query);
                $num_results = $result->num_rows;
                
                for ($i=0; $i <$num_results; $i++) {     
                $row = $result->fetch_assoc();
                array_push($arr, $row['Name']);
                echo '<form class="product" action="product.php" method="get">'; 
                echo '<p class="sqlpar"><strong>'.($i+1).'.  ';
                //Make each product clickable for redirect to their product page
                echo '<input type="hidden" name="cartproduct_id" value="'.stripslashes($row['Id']).'">';
                echo '<input type="hidden" name="product_id" value="'.stripslashes($row['Name']).'">';
                echo '<input type="hidden" name="product_price" value="'.stripslashes($row['Price']).'">';
                echo '<input type="submit" class="link" style="margin-left: 0;" name="product_name" value="'.stripslashes($row['Name']).'"/>';
                echo '</strong><br /> Price: ';  
                echo stripslashes($row['Price']);     
                echo "</p>";
                echo '</form>';
                echo '<form class="product" action="" method="post">'; 
                echo '<input type="hidden" name="cartproduct_id" value="'.stripslashes($row['Id']).'">';
                echo '<input type="hidden" name="product_price" value="'.stripslashes($row['Price']).'">';
                echo '<input type="submit" style="margin-left: 20px;" class="button-border" name="remove_product" value="Remove product"/>';
                echo '</form>';
                

            }
            } else {
                echo 'You currently have no cart.';
            }
        ?>
        
        <p class="product-info"><br /><strong> Total Price: </strong><?php echo $price?></p>
        <p class="product-info"><br /><strong> Total Quantity: </strong><?php echo $quantity?></p>
        
        <form style="margin-left: 40px; margin-top: 20px;" class="cartForm" action="" method="post">
            <input type="submit" name="submitCart" value="Submit Cart"/>
        </form>
    </div>
    <?php
    // Remove product from cart
        //print_r($arr);
        if ((isset($_POST['remove_product']))) {
            $prod_id = $_POST['cartproduct_id'];
            $prod_price = $_POST['product_price'];
            // Delete item from cart_products table
            $query = 'DELETE FROM `Cart_Products` WHERE Id = "'.$prod_id.'"';
            $result = $db->query($query);
            
            // Adjust cart price and quantity
            $query = 'DELETE FROM `Cart_Products` WHERE Id = "'.$prod_id.'"';
            $result = $db->query($query);
            
            // If only one product in cart delete cart
            if ($quantity == 1) {
                $query = 'DELETE FROM `Cart` WHERE Client_id = "'.$user_id.'"';
                $result = $db->query($query);
            } else {
                // Update cart
                $quantity -= 1;
                $price -= $prod_price;
                
                $query = 'UPDATE `Cart` SET Price = '.$price.', Quantity = '.$quantity.' WHERE Client_id = '.$user_id.'';
                $result = $db->query($query); 
            }
            echo '<meta http-equiv = "refresh" content = "1; url = cart.php" />';
        }
    ?>
    <?php
    // Submit cart
        //print_r($arr);
        if ((isset($_POST['submitCart']))) {
            // Check if user has a cart
            $query = 'select * from `Cart` where Client_id = "'.$user_id.'"';
            $result = $db->query($query);
            $num_results = $result->num_rows;
            if ($num_results > 0) {
                
                // Remove 1 stock from each product in order
                foreach ($arr as &$value) {
                    $query = 'SELECT Stock_Amount FROM `Stock` WHERE Name = "'.$value.'"';
                    $result = $db->query($query);
                    while ($row = $result->fetch_assoc()) {
                        $stk = $row['Stock_Amount'];
                    }
                    
                    $stk -= 1;
                    $query = 'UPDATE `Stock` SET Stock_Amount = '.$stk.' WHERE Name = "'.$value.'"';
                    $result = $db->query($query);
                }
                
                // Insert into orders
                echo ' ' . $cart_id . ' ' . $user_id . ' ' . $price . ' ' . date("Y-m-d"); 
                $query = 'INSERT INTO `Order_Summary` (Cart_id,Client_id,Total_Price,Product_Quantity,Order_date,Delivered,Tracking_Number) VALUES ('.$cart_id.','.$user_id.','.$price.','.$quantity.',"'.date("Y-m-d").'", 0, '.$cart_id.')';
                $result = $db->query($query);
                $num_results = $result->num_rows;
                if ($num_results > 0) {
                    echo 'Created new order';
                }
                // Delete user's cart
                $query = 'DELETE FROM `Cart` WHERE Id = '.$cart_id.'';
                $result = $db->query($query);
                $num_results = $result->num_rows;
                if ($num_results > 0) {
                //    echo 'Deleted old cart';
                }
                echo '<meta http-equiv = "refresh" content = "1; url = cart.php" />';
            }
        }
                
    ?>
</body>
</html>