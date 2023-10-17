<?php
    include('php/session.php');

    //echo $user_check;
    $user = $login_session;
    
    // Get user Id
    $ses_sql = mysqli_query($db,'select `Id` from `Client` where Username = "'.$user.'"');
   
    $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
    $user_id = $row['Id'];
    
    // Get user account level
    $acntlvl = -1;
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
    //json_encode($acntlvl);

    // Get id
    //$ses_sql = mysqli_query($db,'select * from `Stock` where Name = "'.$product_name.'" ');
    
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
    <title>Profile Page</title>
</head>
<body>
    <div style="margin-left: 50px;" class="product-body">
        <h1 class="pTitle" style="margin-top: 100px;">Profile Page</h1>
        <?php
            if ($acntlvl == 1) {
                
                $ses_sql = mysqli_query($db,'select * from `Client` where Username = "'.$user.'" ');
    
                $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
                
                echo '<div class="">';
                echo '<p><strong> First Name: </strong></p>';  
                echo '<p>"'.stripslashes($row['Fname']).'"</p>';
                echo '<p><strong> Last Name: </strong></p>';  
                echo '<p>"'.stripslashes($row['Lname']).'"</p>';
                echo '<p><strong> Username: </strong></p>';  
                echo '<p>"'.stripslashes($row['Username']).'"</p>';
                echo '<p><strong> Shipping Address: </strong></p>';  
                echo '<p>"'.stripslashes($row['Shipping_Address']).'"</p>';
                echo '<p><strong> Billing Address: </strong></p>';  
                echo '<p>"'.stripslashes($row['Billing_Address']).'"</p>';  
                echo '</div>';
            } else if  ($acntlvl == 2) {
                $ses_sql = mysqli_query($db,'select * from `Employee` where Username = "'.$user.'" ');
    
                $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
                
                echo '<div class="">';
                echo '<p><strong> First Name: </strong></p>';  
                echo '<p>"'.stripslashes($row['Fname']).'"</p>';
                echo '<p><strong> Last Name: </strong></p>';  
                echo '<p>"'.stripslashes($row['Lname']).'"</p>';
                echo '<p><strong> Username: </strong></p>';  
                echo '<p>"'.stripslashes($row['Username']).'"</p>';
                echo '<p><strong> Salary: </strong></p>';  
                echo '<p>"'.stripslashes($row['Salary']).'"</p>';
                echo '<p><strong> Address: </strong></p>';  
                echo '<p>"'.stripslashes($row['Address']).'"</p>';  
                echo '</div>';
            } else if ($acntlvl == 3) {
                
                $ses_sql = mysqli_query($db,'select * from `Owner` where Username = "'.$user.'" ');
    
                $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
                echo '<div class="">';
                echo '<p><strong> First Name: </strong></p>';  
                echo '<p>"'.stripslashes($row['Fname']).'"</p>';
                echo '<p><strong> Last Name: </strong></p>';  
                echo '<p>"'.stripslashes($row['Lname']).'"</p>';
                echo '<p><strong> Username: </strong></p>';  
                echo '<p>"'.stripslashes($row['Username']).'"</p>';
                echo '<p><strong> Profit: </strong></p>';  
                echo '<p>"'.stripslashes($row['Profit']).'"</p>';
                echo '<p><strong> Address: </strong></p>';  
                echo '<p>"'.stripslashes($row['Address']).'"</p>';  
                echo '</div>';
            }
        ?>
    </div>
</body>
</html>