<?php
if(isset($_POST["css"])) {
    $css = $_POST["css"];
    $path = "resources/style.css";
    file_put_contents($path, $css);
    header("location: /admin/cssEditor");
}
exit();