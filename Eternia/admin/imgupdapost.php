<?php
session_start();
$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $id = trim($_POST['id']);
    $imgText = htmlspecialchars(trim($_POST['imgText']), ENT_QUOTES);
    $imgDatd = trim($_POST['imgDatd']);
    $imgUrl = htmlspecialchars(trim($_POST['imgUrl']), ENT_QUOTES);

    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("UPDATE loveimg SET imgText = ?, imgDatd = ?, imgUrl = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sssi", $imgText, $imgDatd, $imgUrl, $id);
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

