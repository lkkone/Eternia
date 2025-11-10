<?php
session_start();

$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $id = $_POST['id'];
    $title = htmlspecialchars(trim($_POST['articletitle']), ENT_QUOTES);
    $text = trim($_POST['articletext']);

    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("UPDATE article SET articletitle = ?, articletext = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("ssi", $title, $text, $id);
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

