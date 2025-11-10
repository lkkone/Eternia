<!DOCTYPE html>
<html lang="zh-CN">
<?php
include_once 'admin/database.php';
$sql = "select * from iperror where State=? limit 1";
$stmt=$conn->prepare($sql);
$stmt->bind_param("s",$ip);
$ip = $_SERVER['REMOTE_ADDR'];
$stmt->bind_result($id,$ipAdd,$Time,$ip_error_text,$text);
$result = $stmt->execute();
if(!$result) echo "错误信息：".$stmt->error;
$stmt->fetch();

?>
<head>
    <meta charset="utf-8"/>
    <title>抱歉 您的IP已被封禁</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>

    <!-- App css -->
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css"/>
    <link href="/style/css/loading.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
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

    body {
        font-family: 'Noto Serif SC', serif;
        font-weight: 700;
    }

    span.badge.badge-danger-lighten {
        font-size: 1.1rem;
    }
    span.badge.badge-success-lighten {
        font-size: 1.1rem;
        margin-bottom: 1rem;
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
                                        style="color: #fff;font-size: 1.3rem;font-family: '宋体';font-weight: 700;">你的行为已被记录</span>
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Eternia 5.2.0</h4>
                            <p class="text-muted">善语结善缘 恶语伤人心</p>
                            <p class="text-muted mb-4">
                                <span class="badge badge-success-lighten">封禁时间：
                                <?php if ($Time) { ?><?php echo $Time; ?><?php } else { ?>无 <?php } ?>
                                </span>
                                <span class="badge badge-danger-lighten">封禁原因：
                                <?php if ($text) { ?><?php echo $text; ?><?php } else { ?>您的IP未被封禁 <?php } ?>
                                </span>

                            </p>
                        </div>
                        <div class="text-center w-75 m-auto" style="margin-bottom: 40px!important;">
                            <?php if ($text){?> <img src="/style/img/WechatIMG42010.gif" style="width: 100%;" alt=""> <?php } ?>

                        </div>

                        <div class="form-group mb-0 text-center">
                            <a href="https://wpa.qq.com/msgrd?v=3&uin=1226530985&site=qq&menu=yes" target="_blank" rel="noopener noreferrer">
                                <button
                                        class="btn btn-primary" type="button"> 申诉解封
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
    Copyright © 2022 Likaikai & Eternia All
    Rights Reserved.
</footer>

<!-- App js -->
<script src="style/jquery/jquery.min.js"></script>
<script>
    $(function () {
        $("#Loadanimation").fadeOut(1000);
    });
</script>
<script src="/admin/assets/js/app.min.js"></script>
</body>

</html>

