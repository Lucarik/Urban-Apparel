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
    $query = 'select * from `Employee`';
    $result = $db->query($query);
    $num_results = $result->num_rows;
    if ($num_results > 0) {
        $hasemployee = true;
        for ($i=0; $i <$num_results; $i++) {     
            $row = $result->fetch_assoc();
            $id = $row['Id'];
            $fname = $row['Fname'];
            $lname = $row['Lname'];
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="css/styles.css" rel="stylesheet">
    <title>Manage Employees</title>
</head>
<body>
    <div class="product-body">
        <h1 class="pTitle" style="margin-left: 50px; margin-top: 100px;">Manage Employees</h1>
        <?php
            // If user has a cart display items
            if ($hasemployee) {
                $query = 'SELECT CONCAT(Fname, " ", Lname) AS "Fullname", Username, Salary, Address , Id FROM `Employee`';
                $result = $db->query($query);
                $num_results = $result->num_rows;
                
                //Display employees, include delete button after information
                for ($i=0; $i <$num_results; $i++) {
                    echo '<div class="">';
                    $row = $result->fetch_assoc();
                    echo '<span id="'.stripslashes($row['Username']).'" class="span"><form action="" method="post">';
                    echo '<p class="sqlpara"><strong>[ '.stripslashes($row['Id']).' ]: </strong>';    
                    echo stripslashes($row['Fullname']);
                    echo "  |  ";   
                    echo stripslashes($row['Username']);
                    echo "  |  "; 
                    echo stripslashes($row['Salary']);
                    echo "  |  ";   
                    echo stripslashes($row['Address']);
                    echo "     ";
                    echo '<input type="hidden" name="empl_id" value="'.stripslashes($row['Id']).'">';
                    echo '<input type="submit" class="link" name="delUser" value="Delete User"/>';
                    echo "</p>";   
                    echo '</form></span>';
                    echo '</div>';
                
                } 
            } else {
                echo 'You currently have no employees.';
            }
        ?>
    </div>
    
    <?php
        if (isset($_POST['delUser'])) {
            $eid = $_POST['empl_id'];
            
            $query = 'DELETE FROM Employee WHERE Id = "'.$eid.'"';
            $result = $db->query($query);
            echo '<meta http-equiv = "refresh" content = "1; url = manage_employees.php" />';
        
        }
    ?>
    <script src="js/sidebar.js"></script>
</body>
</html>