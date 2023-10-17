<?php
    include('php/session.php');
?>
<!DOCTYPE html>
<html>
<head>
    <link href="css/styles.css" rel="stylesheet">
    <meta charset="utf-8" />
    <title>Add Product page</title>
</head>

<body class="decalr">
    <h1 class="floating-titler">Urban Apparel</h1>
    <div class="content">
        <form enctype="multipart/form-data" class="register-form" style="border: 1px solid darkgrey; width: 60%;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <h2 class="r2header">Add Product</h2>
            <span>
                <label for="pname" class="desc">Name</label>
                <input type="text" class="reg" id="pname" name="pname">
                <label for="price" class="desc">Price</label>
                <input type="text" class="reg" id="price" name="price">
                <label for="color" class="desc">Color</label>
                <input type="text" class="reg" id="color" name="color">
                <label for="size" class="desc">Size</label>
                <select class="select_desc" name="size">
                  <option value="Small">Small</option>
                  <option value="Medium">Medium</option>
                  <option value="Large">Large</option>
                  <option value="Extra Large">Extra Large</option>
                </select>
                <label for="type" class="desc">Type</label>
                <select class="select_desc" name="type">
                  <option value="Shirt">Shirt</option>
                  <option value="Pants">Pants</option>
                  <option value="Shoes">Shoes</option>
                  <option value="Headwear">Headwear</option>
                  <option value="Jacket">Jacket</option>
                </select>
                <label for="sex" class="desc">Sex</label>
                <select class="select_desc" name="sex">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
                <label for="userfile" class="desc">Image</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                <input style="width:96%;background:none;" name="userfile" type="file" accept="image/*"/>
            </span>
            <button class="form-button" type="submit" name="confirm" id="confirm">Confirm</button>
        </form>
    </div>

    <?php
        // define variables and set to empty values
    $pname = $price = $color = $type = $size = $sex = "";
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $pname = test_input($_POST["pname"]);
        $price = test_input($_POST["price"]);
        $size = test_input($_POST["size"]);
        $type = test_input($_POST["type"]);
        $color = test_input($_POST["color"]);
        $sex = test_input($_POST["sex"]);

        $dbUser = get_cfg_var('dbUser');
        $dbPass = get_cfg_var('dbPass');
        $dbName = get_cfg_var('dbName');
        //Initialize database connection
        
        $uploaddir = 'images/';
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
        //echo $uploadfile;
        //echo basename($_FILES['userfile']['type']);
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            //echo "<p>File is valid, and was successfully uploaded.</p>";
            $image_name = basename($_FILES['userfile']['name']);

            //$result = $db->query("UPDATE `Stock` SET Image = '$image_name' WHERE Id = '$user_id'");
            //header("Refresh:0");
        } else {
            //echo "<p>Upload failed</p>";
        }
        //echo '<pre>';
        //echo 'Here is some more debugging info:';
        //print_r($_FILES);
        //print "</pre>";

        $valid = false;
                
        //Checks if any of the inputs contain illegal characters
        if ((preg_match("/^[A-Z][A-Za-z0-9-\s]{2,30}/", $pname)) &&
            (preg_match("/[0-9.]{2,10}/", $price)) &&
            (preg_match("/^[A-Z][a-z]{2,20}/", $color))) {
                
            $valid = true;
            
        }
        $empty = false;
        //Check for empty input value
        if ((empty($pname)) || 
            (empty($price)) || 
            (empty($size)) || 
            (empty($color)) || 
            (empty($type)) ||
            (empty($sex))) {
                
            $empty = true;    
        }
        
        //Insert new user into database if no illegal input
        if (($valid == true) && ($empty == false)) {
            //echo '<p class="sqlpara">Valid-Empty: [ '.$valid.' ] [ '.$empty.' ]</p>';
            
            $query = 'INSERT INTO `Stock` (Name,Price,Size,Type,Color,Sex,Feature,Stock_Amount,Image) VALUES ("'.$pname.'",'.$price.',"'.$size.'","'.$type.'","'.$color.'","'.$sex.'",0,0,"'.$image_name.'")';
            $result = $db->query($query);
            
            $query = 'SELECT * FROM `Stock` WHERE `Name` = "'.$pname.'"';
            $result = $db->query($query);
            $num_results = $result->num_rows;
            
            if ($num_results > 0) {
                echo '<p class="sqlpara">Added Product [ '.$pname.' ]</p>';
                //header("location:login.php");
            } else {
                echo '<p class="sqlpara">Failed to add product. Please recheck input parameters</p>';
            }
            
        } elseif ($valid == false) {
            echo '<p class="sqlpara">Input contains illegal characters. </p>';
        } elseif ($empty == true) {
            echo '<p class="sqlpara">An input is empty. Please fill in inputs. </p>';
        }
        $db->close();
    }
    ?>
    <script src="js/sidebar.js"></script>

</body>
</html>