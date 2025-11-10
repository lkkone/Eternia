<?php
session_start();
$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $title = htmlspecialchars(trim($_POST['articletitle']), ENT_QUOTES);
    $text = trim($_POST['articletext']);
    $name = trim($_POST['articlename']);
    $time = gmdate("Y-m-d", time() + 8 * 3600);
    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("INSERT INTO article (articletitle, articletext, articletime, articlename) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $title, $text, $time, $name);
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
