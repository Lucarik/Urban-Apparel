<?php
session_start();
include('php/session.php');
//This will setup what type of catalog the user will see.
if (isset($_POST["picked_shirts"]) || !isset($_SESSION["catalog type"])) {
    $_SESSION["catalog type"] = 1;
}
else if (isset($_POST["picked_pants"])) {
    $_SESSION["catalog type"] = 2;
}
else if (isset($_POST["picked_shoes"])) {
    $_SESSION["catalog type"] = 3;
}
else if (isset($_POST["picked_headwear"])) {
    $_SESSION["catalog type"] = 4;
}
else if (isset($_POST["picked_jackets"])) {
    $_SESSION["catalog type"] = 5;
}
else if (isset($_POST["clothing_filter"])) {
    $_SESSION["small clothing"] = (isset($_POST["picked_small"])) ? true : false;
    $_SESSION["medium clothing"] = (isset($_POST["picked_medium"])) ? true : false;
    $_SESSION["large clothing"] = (isset($_POST["picked_large"])) ? true : false;
    $_SESSION["x-large clothing"] = (isset($_POST["picked_x_large"])) ? true : false;
    $_SESSION["catalog order mode"] = (int)$_POST["picked_order"];
}
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
        <h1 class="webpage_label">WOMEN CATALOG</h1>
        <form method="post">
            <div class="clothing_selection_section_left">
                <div class="clothing_selection_section_left_area">
                    <input name="picked_shirts" class="clothing_selection_button" type="submit" <?php if ( $_SESSION["catalog type"] === 1) { echo "style='color: gold;'"; } ?> value="SHIRTS">
                    <input name="picked_pants" class="clothing_selection_button" type="submit" <?php if ( $_SESSION["catalog type"] === 2) { echo "style='color: gold;'"; } ?> value="PANTS">
                    <input name="picked_shoes" class="clothing_selection_button" type="submit" <?php if ( $_SESSION["catalog type"] === 3) { echo "style='color: gold;'"; } ?> value="SHOES">
                    <input name="picked_headwear" class="clothing_selection_button" type="submit" <?php if ( $_SESSION["catalog type"] === 4) { echo "style='color: gold;'"; } ?> value="HEADWEAR">
                    <input name="picked_jackets" class="clothing_selection_button" type="submit" <?php if ( $_SESSION["catalog type"] === 5) { echo "style='color: gold;'"; } ?> value="JACKETS">
                </div>
                <div>
                    <input name="picked_small" id="select_small" class="clothing_selection_checkbox" type="checkbox" <?php if ($_SESSION["small clothing"]) { echo "checked"; } ?>>
                    <label class="clothing_selection_label" for="select_small">SMALL</label>
                    <input name="picked_medium" id="select_medium" class="clothing_selection_checkbox" type="checkbox" <?php if ($_SESSION["medium clothing"]) { echo "checked"; } ?>>
                    <label class="clothing_selection_label" for="select_medium">MEDIUM</label>
                    <input name="picked_large" id="select_large" class="clothing_selection_checkbox" type="checkbox" <?php if ($_SESSION["large clothing"]) { echo "checked"; } ?>>
                    <label class="clothing_selection_label" for="select_large">LARGE</label>
                    <input name="picked_x_large" id="select_x_large" class="clothing_selection_checkbox" type="checkbox" <?php if ($_SESSION["x-large clothing"]) { echo "checked"; } ?>>
                    <label class="clothing_selection_label" for="select_x_large">X-LARGE</label>
                </div>
            </div>
            <div class="clothing_selection_section_right">
                <div class="clothing_selection_section_right_area">
                     <select name="picked_order" class="clothing_selection_order">
                        <option value="0" <?php if ($_SESSION["catalog order mode"] === 0) { echo "selected"; } ?>>NO ORDER</option>
                        <option value="1" <?php if ($_SESSION["catalog order mode"] === 1) { echo "selected"; } ?>>LOW TO HIGH PRICE</option>
                        <option value="2" <?php if ($_SESSION["catalog order mode"] === 2) { echo "selected"; } ?>>HIGH TO LOW PRICE</option>
                        <option value="3" <?php if ($_SESSION["catalog order mode"] === 3) { echo "selected"; } ?>>SMALL TO BIG PRODUCT</option>
                        <option value="4" <?php if ($_SESSION["catalog order mode"] === 4) { echo "selected"; } ?>>BIG TO SMALL PRODUCT</option>
                        <option value="5" <?php if ($_SESSION["catalog order mode"] === 5) { echo "selected"; } ?>>ALPHABETICAL</option>
                        <option value="6" <?php if ($_SESSION["catalog order mode"] === 6) { echo "selected"; } ?>>REVERSE ALPHABETICAL</option>
                    </select>
                </div>
                <div>
                    <input name="clothing_filter" class="filter_button" type="submit" value="FILTER">
                </div>
            </div>
        </form>
        <?php
        $clothing_statement = "SELECT * FROM Stock WHERE Sex = 'Female'";
        switch ($_SESSION["catalog type"]) {
            case 1:
                $clothing_statement .= "AND Type = 'Shirt'";
                break;
            case 2:
                $clothing_statement .= "AND Type = 'Pants'";
                break;
            case 3:
                $clothing_statement .= "AND Type = 'Shoes'";
                break;
            case 4:
                $clothing_statement .= "AND Type = 'Headwear'";
                break;
            case 5:
                $clothing_statement .= "AND Type = 'Jacket'";
                break;
        }
        if (!empty( $_SESSION["small clothing"]) || !empty( $_SESSION["medium clothing"]) || !empty( $_SESSION["large clothing"]) || !empty( $_SESSION["x-large clothing"])) {
            $clothing_statement .= " AND (";
            if (!empty( $_SESSION["small clothing"]) && (!empty( $_SESSION["medium clothing"]) || !empty( $_SESSION["large clothing"]) || !empty( $_SESSION["x-large clothing"]))) {
                $clothing_statement .= "Size = 'Small' OR ";
            }
            else if (!empty( $_SESSION["small clothing"])) {
                $clothing_statement .= "Size = 'Small'";
            }
            if (!empty( $_SESSION["medium clothing"]) && (!empty( $_SESSION["large clothing"]) || !empty( $_SESSION["x-large clothing"]))) {
                $clothing_statement .= "Size = 'Medium' OR ";
            }
            else if (!empty( $_SESSION["medium clothing"])) {
            $clothing_statement .= "Size = 'Medium'";
            }
            if (!empty( $_SESSION["large clothing"]) && !empty( $_SESSION["x-large clothing"])) {
                $clothing_statement .= "Size = 'Large' OR ";
            }
            else if (!empty( $_SESSION["large clothing"])) {
                $clothing_statement .= "Size = 'Large'";
            }
            if (!empty( $_SESSION["x-large clothing"])) {
                $clothing_statement .= "Size = 'X-large'";
            }
            $clothing_statement .= ")";
        }
        switch ($_SESSION["catalog order mode"]) {
            default:
                $clothing_statement .= ";";
                break;
            case 1:
                $clothing_statement .= " ORDER BY Price ASC;";
                break;
            case 2:
                $clothing_statement .= " ORDER BY Price DESC;";
                break;
            case 3:
                $clothing_statement .= " ORDER BY FIELD(Size, 'Small', 'Medium', 'Large', 'X-large') ASC;";
                break;
            case 4:
                $clothing_statement .= " ORDER BY FIELD(Size, 'Small', 'Medium', 'Large', 'X-large') DESC;";
                break;
            case 5:
                $clothing_statement .= " ORDER BY Name ASC;";
                break;
            case 6:
                $clothing_statement .= " ORDER BY Name DESC;";
                break;
        }
        $clothing_query = mysqli_query($db, $clothing_statement);
        ?>
        <ul class="clothing_catalog">
            <?php
            while ($clothing_fetch = mysqli_fetch_assoc($clothing_query)) {
            ?>
            <li class="clothing_catalog_item">
                <img class="clothing_catalog_item_image" src="images/<?php echo $clothing_fetch["Image"]; ?>">
                <div class="clothing_catalog_item_name">
                    <?php
                    echo $clothing_fetch["Name"];
                    ?>
                </div>
                <div class="clothing_catalog_item_price">
                    <?php
                    echo "$" . $clothing_fetch["Price"];
                    ?>
                </div>
                <div class="clothing_catalog_item_quantity">
                    <?php
                    echo "QT: " . $clothing_fetch["Stock_Amount"];
                    ?>
                </div>
                <form method="post">
                    <input name="<?php echo "item_" . $clothing_fetch["Id"]; ?>" class="clothing_catalog_item_button" type="submit" value="ADD">
                </form>
                <div class="clothing_catalog_item_cart_count">
                    <?php
                    if (isset($_POST["item_" . $clothing_fetch["Id"]])) {
                        $cart_statement = "SELECT * FROM Cart WHERE Client_id = '" . mysqli_real_escape_string($db, $_SESSION["id user"]) . "';";
                        $cart_query = mysqli_query($db, $cart_statement);
                        if (mysqli_num_rows($cart_query) > 0) {
                            $cart_fetch = mysqli_fetch_array($cart_query);
                            $new_cart_price = $cart_fetch["Price"] + $clothing_fetch["Price"];
                            $new_cart_quantity = $cart_fetch["Quantity"] + 1;
                            $cart_update_query = mysqli_prepare($db, "UPDATE Cart SET Price = ?, Quantity = ? WHERE Client_id = ?;");
                            mysqli_stmt_bind_param($cart_update_query, "dii", $new_cart_price, $new_cart_quantity, $_SESSION["id user"]);
                            mysqli_stmt_execute($cart_update_query);
                            mysqli_stmt_close($cart_update_query);
                        }
                        else {
                            $new_cart_quantity = 1;
                            $cart_insert_query = mysqli_prepare($db, "INSERT INTO Cart (Client_id, Price, Quantity) VALUES (?, ?, ?);");
                            mysqli_stmt_bind_param($cart_insert_query, "idi", $_SESSION["id user"], $clothing_fetch["Price"], $new_cart_quantity);
                            mysqli_stmt_execute($cart_insert_query);
                            mysqli_stmt_close($cart_insert_query);
                            $cart_query = mysqli_query($db, $cart_statement);
                            $cart_fetch = mysqli_fetch_array($cart_query);
                        }
                        $cart_products_statement = "SELECT * FROM Cart_Products Where Cart_Id = '" . mysqli_real_escape_string($db, $cart_fetch["Id"]) . "' AND Product_Id = '" . mysqli_real_escape_string($db, $clothing_fetch["Id"]) . "';";
                        $cart_products_query = mysqli_query($db, $cart_products_statement);
                        if (mysqli_num_rows($cart_products_query) <= 0) {
                            $cart_products_insert_query = mysqli_prepare($db, "INSERT INTO Cart_Products (Cart_Id, Product_Id) VALUES (?, ?);");
                            mysqli_stmt_bind_param($cart_products_insert_query, "ii", $cart_fetch["Id"], $clothing_fetch["Id"]);
                            mysqli_stmt_execute($cart_products_insert_query);
                            mysqli_stmt_close($cart_products_insert_query);
                        }
                        mysqli_close($db);
                    }
                    ?>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </body>
</html>