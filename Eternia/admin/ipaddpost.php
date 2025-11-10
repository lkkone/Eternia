<?php
session_start();
$file = $_SERVER['PHP_SELF'];

include_once 'connect.php';
include_once 'function.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $ip = trim($_POST['ipdz']);
    $bz = trim($_POST['bz']);
    $time = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
    $ipgsd = get_ip_city_New($ip);

    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("INSERT INTO iperror (ipAdd, Time, State, text) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $ipgsd, $time, $ip, $bz);
        $result = $stmt->execute();
        $stmt->close();
    if ($result) {
        echo "1";
        } else {
            echo "0";
        }
    } else {
        echo "0";
    }
} else {
    echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
}
