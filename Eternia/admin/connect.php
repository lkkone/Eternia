<?php
include_once __DIR__.'/config_db.php';
$connect = mysqli_connect($db_address,$db_username,$db_password,$db_name);
$Eternia_Code = $Like_Code;
if (!$connect) {
    die("<script>location.href = '../admin/connectdie.php';</script>");
}
$connect->set_charset("utf8mb4");