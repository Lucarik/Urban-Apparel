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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="css/styles.css" rel="stylesheet">
    <title>Product Page</title>
</head>
<body>
    <div class="product-body">
        <h1 class="pTitle" style="margin-left: 50px; margin-top: 100px;">Product Page</h1>
        <img src="<?php echo $image_path;?>" alt="Product Picture" style="max-height:40%; max-width:20%; margin-left: 50px; border: 1px solid lightgrey;">
        <p class="product-info"><br /><strong> Name: </strong><?php echo $product_name?></p>
        <p class="product-info"><br /><strong> Price: </strong><?php echo $product_price?></p>
        <p class="product-info"><br /><strong> Size: </strong><?php echo $product_size?></p>
        <p class="product-info"><br /><strong> Type: </strong><?php echo $product_type?></p>
        <p class="product-info"><br /><strong> Color: </strong><?php echo $product_color?></p>
        <p class="product-info"><br /><strong> Stock Remaining: </strong><?php echo $product_stock?></p>
        
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