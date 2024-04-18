<?php
session_destroy();
$_SESSION = [];
header("location: /");