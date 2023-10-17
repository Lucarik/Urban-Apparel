<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/styles.css" rel="stylesheet">
    <title>View Products</title>

</head>
<body style="margin-left: 10px;"class="body">
    <nav class="navigation_bar">
            <a class="home_link" href="urban_apparel_home.php">URBAN APPAREL</a>
            <a class="nav_link" href="urban_apparel_men_catalog.php">MEN</a>
            <a class="nav_link" href="urban_apparel_women_catalog.php">WOMEN</a>
            <a class="profile_link" href="urban_apparel_profile.php"><?php echo $_SESSION['login_user']; ?></a>
        </nav>
         <?php
        include('php/user_check.php');
        ?>
    <div style="margin-left: 20px;"class="search">
        <span style="margin-left: 0;" class="span">
        <form style="display:flex; flex-direction:row;" action="" method="post">
        <span style="margin:0 10px;padding:0;">
        <label for="type">Product type:</label>
        
        <select name="type">
          <option value="all">All</option>
          <option value="Shirt">Shirt</option>
          <option value="Pants">Pants</option>
          <option value="Jacket">Jacket</option>
          <option value="Headwear">Headwear</option>
          <option value="Shoes">Shoes</option>
        </select>
        </span>
        <span style="margin:0;padding:0;">
        <label for="sort">Sort by:</label>
        <select name="sort">
          <option value="none">None</option>
          <option value="Name">Name</option>
          <option value="price desc">Price Desc</option>
          <option value="Price">Price Asc</option>

        </select>
        </span>
        
        <input style="height:23px;margin-left:10px;margin-top:15px;" type="submit" name="submitB" value="Search"/>
        
        </form>
        </span>
        <span class="searchSpecs">
            <form style="display:flex; flex-direction:row;" action="" method="post">
            <span style="margin:0 10px;padding:0;">
            <label for="type">Product search:</label>
            <input type="text" name="prodSearch" placeholder="Search name here"></input>
            </span>
            <input style="height:23px;margin-left:10px;margin-top:16px;" type="submit" name="submitS" value="Search"/>
            </form>
        </span>
    </div>
    
    
    <?php
        $dbUser = get_cfg_var('dbUser');
        $dbPass = get_cfg_var('dbPass');
        $dbName = get_cfg_var('dbName');
        //Initialize database connection
        $db = new mysqli('localhost', $dbUser, $dbPass, $dbName);
        //echo mysqli_connect_errno();  
        if (mysqli_connect_errno()) {     
            echo 'Error: Could not connect to database.  Please try again later.';     
            exit;
        }
        //If user selected a search option, run php
        if (isset($_POST['type'])) {
            $selectType = $_POST['type'];
            if (isset($_POST['sort'])) $selectSort = $_POST['sort'];
            if (!get_magic_quotes_gpc()) {    
                $selectType = addslashes($selectType);
                $selectSort = addslashes($selectSort);
            }
        
            //If user didn't specify sorting type
            if ($selectSort == 'none') {
                //If user specified all types return list of all products
                if ($selectType == 'all') {
                    
                    $result = $db->query('SELECT Name, Price FROM `Stock`');
                //Else return products based on type
                } else {
                    $stmt = $db -> prepare('SELECT Name, Price FROM `Stock` where Type = ?');
                    $stmt->bind_param('s', $selectType);
                    $stmt->execute();
                    $result = $stmt->get_result();

                }
                
            } 
            //If sorting type was specified
            else {
                //If type was all products, query sorted all products
                if ($selectType == 'all') {
                    $query = 'SELECT Name, Price FROM `Stock` ORDER BY '.$selectSort;
                    $result = $db->query($query);

                //Else query products based on type and sorting style
                } else  {
                    $query = 'SELECT Name, Price FROM `Stock` where Type = "'.$selectType.'" ORDER BY '.$selectSort;
                    $result = $db->query($query);

                }
            }
            //Return list of all products based on query
            $num_results = $result->num_rows;

            echo 'Number of results: '.$num_results;
            
            for ($i=0; $i <$num_results; $i++) {     
                $row = $result->fetch_assoc();
                
                
                echo '<form class="product" action="urban_apparel_product_page.php" method="get">'; 
                echo '<p class="sqlpar"><strong>'.($i+1).'.  ';
                //Make each product clickable for redirect to their product page
                echo '<input type="hidden" name="product_id" value="'.stripslashes($row['Name']).'">';
                echo '<input type="submit" class="link" style="margin-left: 0;" name="product_name" value="'.stripslashes($row['Name']).'"/>';
                echo '</strong><br /> Price: ';  
                echo stripslashes($row['Price']);     
                echo "</p>";  
                echo '</form>';

            }
            
            //$result->free(); 
            //$db->close();

        }
        
        //If user searches through text field
        if (isset($_POST['submitS'])) {
            $searchparam = $_POST['prodSearch'];
            if (!get_magic_quotes_gpc()) {    
                $searchparam = addslashes($searchparam);
            }
            
            //Query for products based on input search
            $query = 'SELECT Name, Price FROM `Stock` WHERE Name LIKE "%'.$searchparam.'%" ORDER BY Name';
            $result = $db->query($query);
            //Return list of all products based on query
            $num_results = $result->num_rows;
            //$pages = ceil($num_results / 20);
            
            if ($num_results == 0) {
                echo '<p class=sqlpara>No products found that matched "'.$searchparam.'"';
            } else echo 'Number of results: '.$num_results;
            
            for ($i=0; $i <$num_results; $i++) {     
                $row = $result->fetch_assoc();
                
                
                echo '<form class="product" action="urban_apparel_product_page.php" method="get">';
                echo '<p class="sqlpar"><strong>'.($i+1).'.  ';
                //Make each product clickable for redirect to their product page
                echo '<input type="hidden" name="prodName" value="'.stripslashes($row['Name']).'">';
                echo '<input type="submit" class="link" name="product_name" value="'.stripslashes($row['Name']).'"/>';
                echo '</strong><br /> Price: ';  
                echo stripslashes($row['Price']);     
                echo "</p>";  
                echo '</form>';

            }

            
            //$result->free(); 
            //$db->close();
        }
    ?>
    <script src="js/sidebar.js"></script>
    
</body>
</html>