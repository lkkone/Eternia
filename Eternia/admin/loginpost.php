<?php
session_start();
$user = $_POST['adminName'] ?? '';
$pw = $_POST['pw'] ?? '';
include_once "database.php";

$USER = null;
$Login_user = null;
$Login_pw = null;
$id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $USER = $user;

    $sql = "SELECT * FROM login WHERE user = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $USER);
    $stmt->bind_result($id, $Login_user, $Login_pw);
    $result = $stmt->execute();
        if (!$result) {
        echo "错误信息：" . $stmt->error;
        } else {
    $stmt->fetch();
        }
        $stmt->close();
    }
}

if ($USER && $Login_user && $USER == $Login_user) {
    // 使用 password_verify 验证密码
    if (password_verify($pw, $Login_pw)) {
        // 登录成功后重新生成 Session ID，防止 Session 固定攻击
        session_regenerate_id(true);
        $_SESSION['loginadmin'] = $USER;
        echo "<script>alert('登录成功 欢迎进入小站后台管理页面！');location.href = '../admin/index.php';</script>";
    } else {
        die("<script>alert('登录失败，用户名或密码错误！！！');history.back();</script>");
    }
} else {
    die("<script>alert('登录失败，用户名或密码错误！！！');history.back();</script>");
}

