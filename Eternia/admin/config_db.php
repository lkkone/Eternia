<?php
error_reporting(0);
header("Content-Type:text/html; charset=utf8");

$db_address = getenv('DB_HOST') ?: "home-love-db";
$db_username = getenv('DB_USER') ?: "root";
$db_password = getenv('DB_PASS') ?: "love1314520";
$db_name = getenv('DB_NAME') ?: "love_db";
$Like_Code = "love";
$version = 20250903;
