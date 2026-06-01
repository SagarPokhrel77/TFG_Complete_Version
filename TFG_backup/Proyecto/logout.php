<?php
require_once __DIR__ . "/php/encoding.php";
session_start();session_destroy();
header("Location: Login.php");
exit();
?>