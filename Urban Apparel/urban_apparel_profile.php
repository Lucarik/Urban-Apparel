<?php
include('php/session.php');
if ($_SESSION["profile_edit"] && $_POST["manager_profile_form_button"]) {
    $manager_query = mysqli_prepare($db, "SELECT Username, Address FROM Owner WHERE Username = ? LIMIT 1;");
    mysqli_stmt_bind_param($manager_query, "s", $_SESSION["login_user"]);
    mysqli_stmt_execute($manager_query);
    mysqli_stmt_store_result($manager_query);
    mysqli_stmt_bind_result($manager_query, $manager_username, $manager_address);
    mysqli_stmt_fetch($manager_query);
    $username = (!empty($_POST["username_input"])) ? $_POST["username_input"] : $manager_username;
    $address = (!empty($_POST["address_input"])) ? $_POST["address_input"] : $manager_address;
    $manager_update_query = mysqli_prepare($db, "UPDATE Owner SET Username = ?, Address = ? WHERE Username = ? LIMIT 1;");
    mysqli_stmt_bind_param($manager_update_query, "sss", $username, $address, $_SESSION["login_user"]);
    mysqli_stmt_execute($manager_update_query);
    $_SESSION["login_user"] = $username;
    $_SESSION["profile_edit"] = false;
    mysqli_stmt_close($manager_query);
    mysqli_stmt_close($manager_update_query);
}
else if ($_SESSION["profile_edit"] && $_POST["employee_profile_form_button"]) {
    $employee_query = mysqli_prepare($db, "SELECT Username, Address FROM Employee WHERE Username = ? LIMIT 1;");
    mysqli_stmt_bind_param($employee_query, "s", $_SESSION["login_user"]);
    mysqli_stmt_execute($employee_query);
    mysqli_stmt_store_result($employee_query);
    mysqli_stmt_bind_result($employee_query, $employee_username, $employee_address);
    mysqli_stmt_fetch($employee_query);
    $username = (!empty($_POST["username_input"])) ? $_POST["username_input"] : $employee_username;
    $address = (!empty($_POST["address_input"])) ? $_POST["address_input"] : $employee_address;
    $employee_update_query = mysqli_prepare($db, "UPDATE Employee SET Username = ?, Address = ? WHERE Username = ? LIMIT 1;");
    mysqli_stmt_bind_param($employee_update_query, "sss", $username, $address, $_SESSION["login_user"]);
    mysqli_stmt_execute($employee_update_query);
    $_SESSION["login_user"] = $username;
    $_SESSION["profile_edit"] = false;
    mysqli_stmt_close($employee_query);
    mysqli_stmt_close($employee_update_query);
}
else if ($_SESSION["profile_edit"] && $_POST["client_profile_form_button"]) {
    $client_query = mysqli_prepare($db, "SELECT Username, Shipping_Address, Billing_Address FROM Client WHERE Username = ? LIMIT 1;");
    mysqli_stmt_bind_param($client_query, "s", $_SESSION["login_user"]);
    mysqli_stmt_execute($client_query);
    mysqli_stmt_store_result($client_query);
    mysqli_stmt_bind_result($client_query, $client_username, $client_shipping_address, $client_billing_address);
    mysqli_stmt_fetch($client_query);
    $username = (!empty($_POST["username_input"])) ? $_POST["username_input"] : $client_username;
    $shipping_address = (!empty($_POST["shipping_address_input"])) ? $_POST["shipping_address_input"] : $client_shipping_address;
    $billing_address = (!empty($_POST["billing_address_input"])) ? $_POST["billing_address_input"] : $client_billing_address;
    $client_update_query = mysqli_prepare($db, "UPDATE Client SET Username = ?, Shipping_Address = ?, Billing_Address = ? WHERE Username = ? LIMIT 1;");
    mysqli_stmt_bind_param($client_update_query, "ssss", $username, $shipping_address, $billing_address, $_SESSION["login_user"]);
    mysqli_stmt_execute($client_update_query);
    $_SESSION["login_user"] = $username;
    $_SESSION["profile_edit"] = false;
    mysqli_stmt_close($client_query);
    mysqli_stmt_close($client_update_query);
}
else if ($_POST["manager_profile_form_button"] || $_POST["employee_profile_form_button"] || $_POST["client_profile_form_button"]) {
    $_SESSION["profile_edit"] = true;
}
$manager_query = mysqli_prepare($db, "SELECT Username, Address FROM Owner WHERE Username = ? LIMIT 1;");
mysqli_stmt_bind_param($manager_query, "s", $_SESSION["login_user"]);
mysqli_stmt_execute($manager_query);
mysqli_stmt_store_result($manager_query);
mysqli_stmt_bind_result($manager_query, $manager_username, $manager_address);
mysqli_stmt_fetch($manager_query);
$employee_query = mysqli_prepare($db, "SELECT Username, Address FROM Employee WHERE Username = ? LIMIT 1;");
mysqli_stmt_bind_param($employee_query, "s", $_SESSION["login_user"]);
mysqli_stmt_execute($employee_query);
mysqli_stmt_store_result($employee_query);
mysqli_stmt_bind_result($employee_query, $employee_username, $employee_address);
mysqli_stmt_fetch($employee_query);
$client_query = mysqli_prepare($db, "SELECT Username, Shipping_Address, Billing_Address FROM Client WHERE Username = ? LIMIT 1;");
mysqli_stmt_bind_param($client_query, "s", $_SESSION["login_user"]);
mysqli_stmt_execute($client_query);
mysqli_stmt_store_result($client_query);
mysqli_stmt_bind_result($client_query, $client_username, $client_shipping_address, $client_billing_address);
mysqli_stmt_fetch($client_query);
?>
<!DOCTYPE html>
<html>
    <meta charset="utf-8" />
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/styles_user.css" rel="stylesheet">
    <head>
        <title>Urban Apparel</title>
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
    </body>
    <h1 class="webpage_label"><?php echo $_SESSION['first name'] . " " . $_SESSION['last name']; ?></h1>
    <form class="profile_form" method="post">
        <?php
        if (mysqli_stmt_num_rows($manager_query) > 0) {
        ?>
        <label class="profile_label" for="username">USERNAME</label>
        <input name="username_input" id="username" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $manager_username . "' disabled"; } ?>>
        <label class="profile_label" for="address">ADDRESS</label>
        <input name="address_input" id="address" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $manager_address . "' disabled"; } ?>>
        <input name="manager_profile_form_button" class="profile_button" type="submit" value="<?php echo ($_SESSION["profile_edit"]) ? "SUBMIT" : "EDIT"; ?>">
        <?php
        }
        else if (mysqli_stmt_num_rows($employee_query) > 0) {
        ?>
        <label class="profile_label" for="username">USERNAME</label>
        <input name="username_input" id="username" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $employee_username . "' disabled"; } ?>>
        <label class="profile_label" for="address">ADDRESS</label>
        <input name="address_input" id="address" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $employee_address . "' disabled"; } ?>>
        <input name="employee_profile_form_button" class="profile_button" type="submit" value="<?php echo ($_SESSION["profile_edit"]) ? "SUBMIT" : "EDIT"; ?>">
        <?php
        }
        else {
        ?>
        <label class="profile_label" for="username">USERNAME</label>
        <input name="username_input" id="username" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $client_username . "' disabled"; } ?>>
        <label class="profile_label" for="shipping_address">SHIPPING ADDRESS</label>
        <input name="shipping_address_input" id="shipping_address" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $client_shipping_address . "' disabled"; } ?>>
        <label class="profile_label" for="billing_address">BILLING ADDRESS</label>
        <input name="billing_address_input" id="billing_address" class="profile_input" type="text" <?php if (!$_SESSION["profile_edit"]) { echo "value='" . $client_billing_address . "' disabled"; } ?>>
        <input name="client_profile_form_button" class="profile_button" type="submit" value="<?php echo ($_SESSION["profile_edit"]) ? "SUBMIT" : "EDIT"; ?>">
        <?php
        }
        mysqli_stmt_close($manager_query);
        mysqli_stmt_close($employee_query);
        mysqli_stmt_close($client_query);
        ?>
    </form>
</html>