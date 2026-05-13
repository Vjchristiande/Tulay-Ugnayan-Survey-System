<?php
session_start();

if(isset($_GET['confirmed'])) {
    session_destroy();
    header('Location: Home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
</head>
<body>

<script>
    const confirmed = confirm("Are you sure you want to logout?");
    if (confirmed) {
        window.location.href = "logout.php?confirmed=true";
    } else {
        window.history.back();
    }
</script>

</body>
</html>