<!DOCTYPE html>
<html>
<head>
    <link href="css/styles.css" rel="stylesheet">
    <meta charset="utf-8" />
    <title>Register page</title>
</head>

<?php
    // define variables and set to empty values
    $fname = $lname = $user = $pass = $email = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = test_input($_POST["fname"]);
        $lname = test_input($_POST["lname"]);
        $user = test_input($_POST["username"]);
        $pass = test_input($_POST["password"]);
        $b_address = test_input($_POST["b_address"]);
        $s_address = test_input($_POST["s_address"]);

    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<body class="decalr">
    <h1 class="floating-titler">Urban Apparel</h1>
    <div class="content">
        <form class="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <h2 class="r2header">Registration</h2>
            <span>
                <label for="fname" class="desc">First Name</label>
                <input type="text" class="reg" id="fname" name="fname">
                <label for="lname" class="desc">Last Name</label>
                <input type="text" class="reg" id="lname" name="lname">
                <label for="username" class="desc">Username</label>
                <input type="text" class="reg" id="username" name="username">
                <label for="password" class="desc">Password</label>
                <input type="password" class="reg" id="password" name="password">
                <label for="b_address" class="desc">Billing Address</label>
                <input type="text" class="reg" id="b_address" name="b_address">
                <label for="s_address" class="desc">Shipping Address</label>
                <input type="text" class="reg" id="s_address" name="s_address">
            </span>
            <button class="form-button" type="submit" name="confirm" id="confirm">Confirm</button>
        </form>
    </div>

    <?php
        if (!empty($fname)) {

        $dbUser = get_cfg_var('dbUser');
        $dbPass = get_cfg_var('dbPass');
        $dbName = get_cfg_var('dbName');
        //Initialize database connection
        $db = new mysqli('localhost', $dbUser, $dbPass, $dbName);
        
        //echo mysqli_connect_errno();  
        if (mysqli_connect_errno()) {     
            echo 'Error: Could not connect to database.  Please try again later.';     
            exit;
        } else {
            echo 'Connected to database';
        }

        $valid = true;
                
        //Checks if any of the inputs contain illegal characters
        if ((preg_match("/[^A-Za-z]/", $fname)) ||
            (preg_match("/[^A-Za-z]/", $lname)) ||
            (preg_match("/[^A-Za-z0-9]/", $user)) ||
            (preg_match("/[^A-Za-z0-9]/", $pass)) ||
            (preg_match("/[^A-Za-z0-9\s\.\,]/", $b_address)) ||
            (preg_match("/[^A-Za-z0-9\s\.\,]/", $s_address))) {
                
            $valid = false;
            
        }
        
        $empty = false;
        
        //Check for empty input value
        if ((empty($fname)) || 
            (empty($lname)) || 
            (empty($user)) || 
            (empty($pass)) || 
            (empty($b_address)) ||
            (empty($s_address))) {
                
            $empty = true;    
        }
        $pepper = get_cfg_var("pepper");
        $pwd_peppered = hash_hmac("sha256", $pass, $pepper);
        $pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2ID);

        //Insert new user into database if no illegal input
        if (($valid == true) && ($empty == false)) {
            //echo '<p class="sqlpara">Valid-Empty: [ '.$valid.' ] [ '.$empty.' ]</p>';
            
            $query = 'INSERT INTO `Client` (Fname,Lname,Username,Password,Shipping_Address,Billing_Address) VALUES ("'.$fname.'","'.$lname.'","'.$user.'","'.$pwd_hashed.'","'.$s_address.'","'.$b_address.'")';
            $result = $db->query($query);
            
            $query = 'SELECT `Username` FROM `Client` WHERE `Username` = "'.$user.'"';
            $result = $db->query($query);
            $num_results = $result->num_rows;
            
            if ($num_results > 0) {
                echo '<p class="sqlpara">Added User [ '.$user.' ]</p>';
                //header("location:login.php");
                echo '<meta http-equiv = "refresh" content = "1; url = login.php" />';
            } else {
                echo '<p class="sqlpara">Failed to add user. Please recheck input parameters</p>';
            }
            
        } elseif ($valid == false) {
            echo '<p class="sqlpara">Input contains illegal characters. </p>';
        } elseif ($empty == true) {
            echo '<p class="sqlpara">An input is empty. Please fill in inputs. </p>';
        }
        $db->close();
    }
    ?>

</body>
</html>