<?php
session_start();
$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $title = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES);
    $logo = htmlspecialchars(trim($_POST['logo'] ?? ''), ENT_QUOTES);
    $writing = htmlspecialchars(trim($_POST['writing'] ?? ''), ENT_QUOTES);
    $WebPjax = trim($_POST['WebPjax'] ?? '');
    $WebBlur = trim($_POST['WebBlur'] ?? '');
    $headerBarShow = trim($_POST['headerBarShow'] ?? '2');

    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("UPDATE text SET title = ?, logo = ?, writing = ? WHERE id = 1");
    if ($stmt) {
        $stmt->bind_param("sss", $title, $logo, $writing);
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

    // 使用预处理语句防止SQL注入
    $stmt2 = $connect->prepare("UPDATE diyset SET Pjaxkg = ?, Blurkg = ?, headerBarShow = ? WHERE id = 1");
    if ($stmt2) {
        $stmt2->bind_param("sss", $WebPjax, $WebBlur, $headerBarShow);
        $diyresult = $stmt2->execute();
        $stmt2->close();
    if ($diyresult) {
        echo "3";
        } else {
            echo "4";
        }
    } else {
        echo "4";
    }
} else {
    echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
}
