<?php
$file = $_GET['route'] ?? '';
$ip = $_SERVER["REMOTE_ADDR"] ?? '未知';

try {
    $db_address = getenv('DB_HOST') ?: "home-love-db";
    $db_username = getenv('DB_USER') ?: "root";
    $db_password = getenv('DB_PASS') ?: "love1314520";
    $db_name = getenv('DB_NAME') ?: "love_db";

    include_once __DIR__.'/function.php';

    $conn = @new mysqli($db_address, $db_username, $db_password, $db_name);

    if ($conn && !$conn->connect_error && !empty($file)) {
        $conn->set_charset("utf8mb4");
        $ipcharu = "insert into warning (ip,gsd,time,file) values (?,?,?,?)";
        $stmt = $conn->prepare($ipcharu);
        if ($stmt) {
            $gsd = get_ip_city_New($ip);
            $time = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
            $stmt->bind_param("ssss", $ip, $gsd, $time, $file);
            $result = $stmt->execute();
            if (!$result) {
                error_log("警告记录失败: " . $stmt->error);
            }
            $stmt->close();
        }
        $conn->close();
    }
} catch (Exception $e) {
    error_log("Warning page database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8"/>
    <title>警告 非法请求</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>

    <!-- App css -->
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css"/>
    <link href="/style/css/loading.css" rel="stylesheet">
</head>

<style>
    .card {
        border-radius: 15px;
    }

    .card-header.pt-4.pb-4.text-center.bg-primary {
        border-radius: 15px 15px 0 0;
    }

    .btn-primary {
        padding: 10px 25px;
        border-radius: 20px;
    }

    .info {
        margin-bottom: 20px;
        font-size: 1.2rem;
    }

    .authentication-bg {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .account-pages {
        width: 100%;
    }
</style>

<body class="authentication-bg">

<div id="Loadanimation" style="z-index:999999;">
    <div id="Loadanimation-center">
        <div id="Loadanimation-center-absolute">
            <div class="xccx_object" id="xccx_four"></div>
            <div class="xccx_object" id="xccx_three"></div>
            <div class="xccx_object" id="xccx_two"></div>
            <div class="xccx_object" id="xccx_one"></div>
        </div>
    </div>
</div>

<div class="account-pages">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header pt-4 pb-4 text-center bg-primary">
                        <a href="##">
                                <span
                                        style="color: #fff;font-size: 1.3rem;font-family: '宋体';font-weight: 700;">你的行为已被记录到数据库</span>
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Eternia</h4>
                            <div class="info">温馨提示：请停止你的行为<i style="color: #ff9b9b;"><?php echo $ip ?></i></div>
                        </div>
                        <div class="text-center w-75 m-auto" style="margin-bottom: 40px!important;">
                            <img src="/style/img/WechatIMG42010.gif" style="width: 100%;">
                        </div>

                        <div class="form-group mb-0 text-center">
                            <a href="https://wpa.qq.com/msgrd?v=3&uin=1226530985&site=qq&menu=yes" target="_blank" rel="noopener noreferrer">
                                <button
                                        class="btn btn-primary" type="button"> 我有疑问
                                </button>
                            </a>
                        </div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

            </div>
            <!-- end row -->

        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- end container -->
</div>
<!-- end page -->

<footer class="footer footer-alt">
    Copyright © 2022-<?php echo date("Y") ?> Likaikai & Eternia All
    Rights Reserved.
</footer>

<!-- App js -->
<script src="../style/jquery/jquery.min.js"></script>
<script>
    $(function () {
        $("#Loadanimation").fadeOut(1000);
    });
</script>
<script src="/admin/assets/js/app.min.js"></script>
</body>

</html>