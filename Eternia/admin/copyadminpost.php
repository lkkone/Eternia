<?php
session_start();

$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $adminName = trim($_POST['adminName']);
    $icp = trim($_POST['icp']);
    $Copyright = trim($_POST['Copyright']);

    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("UPDATE text SET icp = ?, Copyright = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $icp, $Copyright);
        $result = $stmt->execute();
        $stmt->close();
    if ($result) {
        echo "<script>alert('更新成功');location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('更新失败');history.back();</script>";
        }
    } else {
        echo "<script>alert('更新失败');history.back();</script>";
    }
} else {
    echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
}
