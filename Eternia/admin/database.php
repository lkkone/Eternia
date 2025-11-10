<?php
include_once __DIR__.'/config_db.php';
$conn = new mysqli($db_address,$db_username,$db_password,$db_name);

if ($conn->connect_error) {
    die("<script>location.href = '../admin/connectdie.php';</script>");
}
$conn->set_charset("utf8mb4");