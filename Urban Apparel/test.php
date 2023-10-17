<?php
    include('php/session.php');

    //echo $user_check;
    $user = $login_session;
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link href="css/styles.css" rel="stylesheet">
    <title>Test</title>
</head>
<body class="decal">
    <h1 class="title">Urban Apparel</h1>
    <script src="js/sidebar.js"></script>
    <?php
        echo 'Current user: '.$user.'';
    ?>
</body>

</html>