<?php
session_start();

$name = htmlspecialchars(trim($_POST['eventname'] ?? ''),ENT_QUOTES);
$icon = $_POST['icon'] ?? 0;
$id = $_POST['id'] ?? 0;
$imgurl = $_POST['imgurl'] ?? '';
$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';
if (!empty($imgurl)) {
    $img = htmlspecialchars($imgurl,ENT_QUOTES);
} else {
    $img = 0;
}
if (!$icon || $icon === '0' || $icon === 0) {
    $icon = 0;
} else {
    $icon = $_POST['icon'];
}

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    // 使用预处理语句防止SQL注入
    $stmt = $connect->prepare("UPDATE lovelist SET eventname = ?, icon = ?, imgurl = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sisi", $name, $icon, $img, $id);
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
