<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="css/styles.css" rel="stylesheet">
    <title>Employee Login</title>
</head>
<body class="decal">
    <h1 class="floating-title">UA</h1>
    <div class="content">
        <div class="login">
            <form class="login_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <input class="form-input" type="text" id="username" name="username" placeholder="Enter Username">
                <input class="form-input" type="password" id="password" name="password" placeholder="Enter Password">
                <button class="form-button" type="submit" id="loginB" name="loginB">Login</button>
            </form>
        </div>
    </div>
    <?php
    // define variables and set to empty values
    $user = $pass = "";
    
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = test_input($_POST["username"]);
        $pass = test_input($_POST["password"]);

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
            //echo 'Connected to database';
        }

        // Encrypt password and check against password gotten from database
        
        
        
        $pepper = get_cfg_var('pepper');
        $pwd_peppered = hash_hmac("sha256", $pass, $pepper);
        
        // temp (Hashes password to database)
        //$pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2ID);
        //$query = 'UPDATE `Employee` SET `Password` = "'.$pwd_hashed.'" WHERE `Username` = "'.$user.'"';
        //$result = $db->query($query);
        // End temp
        
        $query = 'SELECT `Password` FROM `Employee` WHERE `Username` = "'.$user.'"';
        $result = $db->query($query);
        while ($row = $result->fetch_assoc()) {
            $pwd_hashed = stripslashes($row['Password']);
        }
        $result->free();
        
        if (password_verify($pwd_peppered, $pwd_hashed)) {
            
            echo "Password matches.";
            $_SESSION['login_user'] = $user;
            
            $usercheck = $_SESSION['login_user'];
            echo $usercheck;
            // Would like to use header(location) but doesn't work
            //header("location:test.php");
            // Using this to change pages for now
            echo '<meta http-equiv = "refresh" content = "1; url = urban_apparel_home.php" />';
        }
        else {

            echo "Password incorrect.";
        }
        //To acquire other user data.
        $profile_statement = "SELECT * FROM Employee WHERE Username = '" . mysqli_real_escape_string($db, $_SESSION['login_user']) . "' LIMIT 1;";
        $profile_query = mysqli_query($db, $profile_statement);
        $profile_fetch = mysqli_fetch_assoc($profile_query);
        $_SESSION['id user'] = $profile_fetch['Id'];
        $_SESSION['first name'] = $profile_fetch['Fname'];
        $_SESSION['last name'] = $profile_fetch['Lname'];
        mysqli_close($db);
    } 

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    ?>
</body>
</html>