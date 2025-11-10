<?php
session_start();
include_once 'connect.php';

$file = $_SERVER['PHP_SELF'];

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $id = $_GET['id'];
    if (is_numeric($id)) {
        // 使用预处理语句防止SQL注入
        $stmt = $connect->prepare("DELETE FROM article WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            $stmt->close();
        if ($result) {
            echo "<script>alert('删除文章成功');location.href = 'littleset.php';</script>";
            } else {
                echo "<script>alert('删除文章失败');history.back();</script>";
            }
        } else {
            echo "<script>alert('数据库操作失败');history.back();</script>";
        }
    } else {
        echo "<script>alert('参数错误');history.back();</script>";
    }

} else {
    echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
}

