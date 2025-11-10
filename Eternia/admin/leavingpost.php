<?php

include_once 'database.php';
include_once 'function.php';

$name = trim($_POST['name'] ?? '');
$qq = trim($_POST['qq'] ?? '');
$text = trim($_POST['text'] ?? '');
$time = time();

// 获取IP地址（function.php 中已定义 $Filter_IP，但需要确保在使用前已定义）
if (!isset($Filter_IP)) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $Filter_IP = trim($list[0]);
    } else {
        $Filter_IP = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

// 过滤特殊字符
$Filter_Name = replaceSpecialChar($name);
$Filter_QQ = replaceSpecialChar($qq);
$Filter_Text = replaceSpecialChar($text);
$Filter_Time = $time; // time() 返回整数，不需要 replaceSpecialChar

$file = $_SERVER['PHP_SELF'];
$result = false;

if (!isset($_COOKIE["KiCookie"])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (is_numeric($qq) && (!empty($Filter_Name)) && (!empty($Filter_Text))) {

            // 验证IP地址格式（允许私有IP，用于开发环境）
            if (filter_var($Filter_IP, FILTER_VALIDATE_IP)) {

                if (checkQQ($qq)) {

                    // 获取城市信息（私有IP直接返回本地）
                    if (filter_var($Filter_IP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        // 公网IP，尝试获取城市信息
                        $User_City = get_ip_city_New($Filter_IP);
                        if (empty($User_City)) {
                            $User_City = '未知';
                        }
                    } else {
                        // 私有IP（127.0.0.1, 192.168.x.x等），直接标记为本地
                        $User_City = '本地';
                    }

                    // 插入留言
                    $charu = "insert into leaving (name,QQ,text,time,ip,city) values (?,?,?,?,?,?)";
                    $stmt = $conn->prepare($charu);
                    if ($stmt) {
                        $stmt->bind_param("sssiss", $Filter_Name, $Filter_QQ, $Filter_Text, $Filter_Time, $Filter_IP, $User_City);
                        $result = $stmt->execute();
                        if (!$result) {
                            error_log("留言插入失败: " . $stmt->error);
                        }
                        $stmt->close();
                    } else {
                        error_log("SQL准备失败: " . $conn->error);
                    }

                } else {
                    echo "3";
                    exit();
                }
            } else {
                echo "4";
                exit();
            }
        } else {
            echo "5";
            exit();
        }

        if ($result) {
            echo "1";
            setcookie("KiCookie", $Filter_IP, time() + 3600 * 24, '/');
        } else {
            echo "0";
        }
    } else {
        echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
    }
} else {
    echo "8";
}


