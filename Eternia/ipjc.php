<?php

include_once 'admin/connect.php';

$ipchaxun = "select * from iperror";
$stmt_ip = $connect->prepare($ipchaxun);
if ($stmt_ip) {
    $stmt_ip->execute();
    $ipres = $stmt_ip->get_result();
while ($IPinfo = mysqli_fetch_array($ipres)) {

    $iplist = $IPinfo['State'];

    $banned_ip = array($iplist);

    $ip = $_SERVER["REMOTE_ADDR"];

    if (in_array(getenv("REMOTE_ADDR"), $banned_ip)) {
        die ("<script>alert('你的IP($ip)已被封禁，禁止访问本页面');location.href = 'error.php';</script>");

    }
    }
    $stmt_ip->close();
}
