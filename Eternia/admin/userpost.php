<?php
session_start();
$file = $_SERVER['PHP_SELF'];
include_once 'connect.php';
if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {

    $adminName = trim($_POST['adminName'] ?? '');
    $pw = trim($_POST['pw'] ?? '');
    $user = trim($_POST['userQQ'] ?? '');
    $name = trim($_POST['userName'] ?? '');
    $Webanimation = trim($_POST['Webanimation'] ?? '');
    $cssCon = trim($_POST['cssCon'] ?? '');
    $headCon = htmlspecialchars(trim($_POST['headCon'] ?? ''), ENT_QUOTES);
    $footerCon = htmlspecialchars(trim($_POST['footerCon'] ?? ''), ENT_QUOTES);
    $SCode = trim($_POST['SCode'] ?? '');

    $pattern = '/[`~!#$^&*()=|{}:;,\[\].<>\/?\~！#￥……&*（）——|{}【】；：""\'。，、？]/';

    if (preg_match($pattern, $adminName)) {
        echo "8";
        exit();
    }

    if (!empty($pw)) {
        if (strlen($pw) <= 6) {
            echo "9";
            exit();
        }
        if (preg_match($pattern, $pw)) {
            echo "10";
            exit();
        }
        if (!preg_match('/[a-zA-Z0-9]/', $pw)) {
            echo "11";
            exit();
        }
    }

    if ($Eternia_Code == $SCode) {
        $stmt = $connect->prepare("UPDATE text SET userQQ = ?, userName = ?, animation = ?");
        if ($stmt) {
            $stmt->bind_param("sss", $user, $name, $Webanimation);
            $result = $stmt->execute();
            $stmt->close();
        } else {
            $result = false;
        }

        if ($pw) {
            // 使用 password_hash 生成安全的密码哈希
            $hashedPw = password_hash($pw, PASSWORD_BCRYPT);
            $stmt2 = $connect->prepare("UPDATE login SET user = ?, pw = ? WHERE id = 1");
            if ($stmt2) {
                $stmt2->bind_param("ss", $adminName, $hashedPw);
                $loginresult = $stmt2->execute();
                $stmt2->close();
            session_destroy();
            } else {
                $loginresult = false;
            }
        } else {
            $stmt2 = $connect->prepare("UPDATE login SET user = ? WHERE id = 1");
            if ($stmt2) {
                $stmt2->bind_param("s", $adminName);
                $loginresult = $stmt2->execute();
                $stmt2->close();
            } else {
                $loginresult = false;
            }
        }

        if ($loginresult) {
            echo "1";
        } else {
            echo "0";
        }
        if ($result) {
            echo "3";
        } else {
            echo "4";
        }

        $stmt3 = $connect->prepare("UPDATE diyset SET headCon = ?, footerCon = ?, cssCon = ?");
        if ($stmt3) {
            $stmt3->bind_param("sss", $headCon, $footerCon, $cssCon);
            $diyresult = $stmt3->execute();
            $stmt3->close();
        if ($diyresult) {
            echo "5";
            } else {
                echo "6";
            }
        } else {
            echo "6";
        }
    } else {
        echo "7";
    }

} else {
    echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
}

