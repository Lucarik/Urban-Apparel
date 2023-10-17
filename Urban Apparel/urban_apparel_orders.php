<?php
    include('php/session.php');

    //echo $user_check;
    $user = $login_session;
    
    // Get user Id
    $ses_sql = mysqli_query($db,'select `Id` from `Client` where Username = "'.$user.'"');
   
    $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
    $user_id = $row['Id'];
    
    // Check if user has an order
    $hasorders = false;
    $query = 'select * from `Order_Summary` where Client_id = "'.$user_id.'"';
    $result = $db->query($query);
    $num_results = $result->num_rows;
    if ($num_results > 0) {
        $hasorders = true;

    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="css/styles.css" rel="stylesheet">
    <title>Orders Page</title>
</head>
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
    <div class="product-body">
        <h1 class="pTitle" style="margin-left: 50px; margin-top: 100px;">Orders</h1>
        <?php
            // If user has orders display them
            if ($hasorders) {
                
                $query = 'SELECT * FROM `Order_Summary` WHERE `Client_id` = '.$user_id.'';
                $result = $db->query($query);
                $num_results = $result->num_rows;
                
                for ($i=0; $i <$num_results; $i++) {     
                $row = $result->fetch_assoc();
                array_push($arr, $row['Name']);
                echo '<div style="display: flex; flex-direction:row;" class="product">'; 
                echo '<p style="margin-left:50px;">Order '.($i+1).': ';
                // Display items in each order
                echo '<p><strong> Total Price: </strong>$</p>';  
                echo '<p>"'.stripslashes($row['Total_Price']).'"</p>';
                echo '<p><strong> Product Quantity: </strong></p>';  
                echo '<p>"'.stripslashes($row['Product_Quantity']).'"</p>';
                echo '<p><strong> Order Date: </strong></p>';  
                echo '<p>"'.stripslashes($row['Order_date']).'"</p>';
                echo '<p><strong> Delivered: </strong></p>';  
                echo '<p>"'.stripslashes($row['Delivered']).'"</p>';  
                echo '<p><strong> Tracking Number: </strong></p>';  
                echo '<p>"'.stripslashes($row['Tracking_Number']).'"</p>';  
                echo '</div>';

            }
            } else {
                echo 'You currently have no orders.';
            }
        ?>
    </div>
    
    <script src="js/sidebar.js"></script>
</body>
</html>