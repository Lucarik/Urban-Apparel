<?php
include('php/session.php');
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
        <div class="feature_product_slideshow">
            <?php
            $product_feature = 1;
            $feature_products_query = mysqli_prepare($db, "SELECT Name, Price, Image FROM Stock WHERE Feature = ?;");
            mysqli_stmt_bind_param($feature_products_query, "i", $product_feature);
            mysqli_stmt_execute($feature_products_query);
            mysqli_stmt_bind_result($feature_products_query, $stock_name, $stock_price, $stock_image);
            while (mysqli_stmt_fetch($feature_products_query)) {
            ?>
            <figure class="feature_product_slide">
                <img class="feature_product_image" src="<?php echo "images/" . $stock_image; ?>">
                <figcaption class="feature_product_caption"><?php echo $stock_name . "<br>$" . $stock_price; ?></figcaption>
            </figure>
            <?php
            }
            mysqli_stmt_close($feature_products_query);
            ?>
            <input class="previous_feature_product_slide" type="button" onclick="previous_slide()" value="&#10094;">
            <input class="next_feature_product_slide" type="button" onclick="next_slide()" value="&#10095;">
        </div>
        <script>
            var slide_index = 0;
            var slide_interact = false;
            show_slide(slide_index);
            setTimeout(slide_auto, 5000);
            function slide_auto() {
                if (!slide_interact) {
                    next_slide()
                }
                else {
                    slide_interact = false;
                }
                setTimeout(slide_auto, 5000);
            }
            function previous_slide() {
                show_slide(slide_index -= 1);
                slide_interact = true;
            }
            function next_slide() {
                show_slide(slide_index += 1);
                slide_interact = true;
            }
            function show_slide(n) {
                var slides = document.getElementsByClassName("feature_product_slide");
                if (n < 0) {
                    slide_index = slides.length - 1;
                }
                else if (n >= slides.length) {
                    slide_index = 0;
                }
                for (var a = 0; a < slides.length; a++) {
                    slides[a].style.display = "none";
                }
                slides[slide_index].style.display = "block";
            }
        </script>
    </body>
</html>