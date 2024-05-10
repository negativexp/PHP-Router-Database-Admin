<?php
    session_start();
    if(isset($_SESSION["admin"])) {
        header("location: /admin");
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php");?>
<body>
<main>
    <div class="wrapper">
        <form class="login" method="post" action="/admin/auth">
            <label>
                <span>user: </span>
                <input type="text" name="username" required>
            </label>
            <label>
                <span>password: </span>
                <input type="password" name="password" required>
            </label>
            <input type="submit">
        </form>
    </div>
</main>
</body>
</html>