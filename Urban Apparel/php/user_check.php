<?php
$manager_query = mysqli_prepare($db, "SELECT Username FROM Owner WHERE Username = ?;");
mysqli_stmt_bind_param($manager_query, "s", $_SESSION["login_user"]);
mysqli_stmt_execute($manager_query);
mysqli_stmt_store_result($manager_query);
$employee_query = mysqli_prepare($db, "SELECT Username FROM Employee WHERE Username = ?;");
mysqli_stmt_bind_param($employee_query, "s", $_SESSION["login_user"]);
mysqli_stmt_execute($employee_query);
mysqli_stmt_store_result($employee_query);
if (mysqli_stmt_num_rows($manager_query) > 0) {
?>
<script src="js/sidebar_manager_b.js"></script>
<?php
}
else if (mysqli_stmt_num_rows($employee_query) > 0) {
?>
<script src="js/sidebar_employee_b.js"></script>
<?php
}
else {
?>
<script src="js/sidebar_user.js"></script>
<?php
}
mysqli_stmt_close($manager_query);
mysqli_stmt_close($employee_query);
?>