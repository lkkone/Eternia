<?php
include_once __DIR__.'/config_db.php';
include ($_SERVER['DOCUMENT_ROOT'] . '/ipjc.php');
include_once 'connect.php';
include_once 'function.php';
$stmt = $connect->prepare("SELECT * FROM login WHERE user = ?");
if ($stmt) {
    $adminUser = $_SESSION['loginadmin'] ?? '';
    $stmt->bind_param("s", $adminUser);
    $stmt->execute();
    $loginresult = $stmt->get_result();
if (mysqli_num_rows($loginresult)) {
    $login = mysqli_fetch_array($loginresult);
    } else {
        header("Location: /admin/login.php");
        exit();
    }
    $stmt->close();
} else {
    header("Location: /admin/login.php");
    exit();
}

if (!isset($login)) {
    $login = [];
}

// 使用预处理语句查询 text 表
$stmt = $connect->prepare("SELECT * FROM text");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result)) {
        $text = mysqli_fetch_array($result);
    } else {
        $text = [];
    }
    $stmt->close();
} else {
    $text = [];
}

// 使用预处理语句查询 diyset 表
$stmt = $connect->prepare("SELECT * FROM diyset");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result)) {
        $diy = mysqli_fetch_array($result);
    } else {
        $diy = [];
    }
    $stmt->close();
} else {
    $diy = [];
}
?>


<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>后台管理 — <?php echo isset($text['title']) ? $text['title'] : 'Eternia' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
    <!-- App css -->
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="/style/css/loading.css" rel="stylesheet">
    <link href="../style/toastr/toastr.css" rel="stylesheet">
</head>

<body>

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
    <script src="../style/jquery/jquery.min.js"></script>
    <script>
        $(function () {
            $("#Loadanimation").fadeOut(1000);
        });
    </script>
    <?php
    // 使用预处理语句查询留言数量
    $stmt = $connect->prepare("SELECT count(id) as shu FROM leaving");
    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        $leav = mysqli_fetch_array($res);
        $shu = $leav['shu'] ?? 0;
        $stmt->close();
    } else {
        $shu = 0;
    }

    // 使用预处理语句查询文章数量
    $stmt = $connect->prepare("SELECT count(id) as dian FROM article");
    if ($stmt) {
        $stmt->execute();
        $resdian = $stmt->get_result();
        $didi = mysqli_fetch_array($resdian);
        $diannub = $didi['dian'] ?? 0;
        $stmt->close();
    } else {
        $diannub = 0;
    }

    // 使用预处理语句查询清单数量
    $stmt = $connect->prepare("SELECT count(id) as list FROM lovelist");
    if ($stmt) {
        $stmt->execute();
        $reslsit = $stmt->get_result();
        $listlove = mysqli_fetch_array($reslsit);
        $listnub = $listlove['list'] ?? 0;
        $stmt->close();
    } else {
        $listnub = 0;
    }

    // 使用预处理语句查询图片数量
    $stmt = $connect->prepare("SELECT count(id) as img FROM loveimg");
    if ($stmt) {
        $stmt->execute();
        $resimg = $stmt->get_result();
        $loveimg = mysqli_fetch_array($resimg);
        $imgnub = $loveimg['img'] ?? 0;
        $stmt->close();
    } else {
        $imgnub = 0;
    }

    $adminuser = "admin";
    $adminpw = "admin";
    ?>

    <!--顶部栏 Start-->

    <div class="navbar-custom topnav-navbar">
        <div class="container-fluid">

            <!-- LOGO -->
            <a href="/admin/index.php" class="topnav-logo">
                <span class="topnav-logo-lg">
                    <?php echo isset($text['title']) ? $text['title'] : 'Eternia' ?>
                </span>
                <span class="topnav-logo-sm">
                    <?php echo isset($text['title']) ? $text['title'] : 'Eternia' ?>
                </span>
            </a>

            <ul class="list-unstyled topbar-right-menu float-right mb-0">


                <li class="dropdown notification-list">
                    <a class="nav-link right-bar-toggle" href="/admin/user.php">
                        <i class="dripicons-gear noti-icon"></i>
                    </a>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown"
                        id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="account-user-avatar">
                            <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo isset($text['userQQ']) ? $text['userQQ'] : '' ?>&s=640"
                                alt="user-image" class="rounded-circle">
                        </span>
                        <span>
                            <span class="account-user-name"><?php echo isset($text['userName']) ? $text['userName'] : '' ?></span>
                            <span class="account-position">操作</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                        aria-labelledby="topbar-userdrop">


                        <!-- item-->
                        <a href="user.php" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle mr-1"></i>
                            <span>全局设置</span>
                        </a>

                        <!-- item-->
                        <a href="loginout.php" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-edit mr-1"></i>
                            <span>退出登录</span>
                        </a>


                    </div>
                </li>

            </ul>
            <a class="button-menu-mobile disable-btn">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
            <div class="app-search">
            </div>
        </div>
    </div>

    <!--顶部栏 End-->


    <div class="container-fluid">

        <div class="wrapper">
            <!--侧边导航栏 Start-->
            <div class="left-side-menu">
                <div class="leftbar-user">
                    <a href="#">
                        <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo isset($text['userQQ']) ? $text['userQQ'] : '' ?>&s=640" alt="user-image"
                            height="42" class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name"><?php echo isset($text['title']) ? $text['title'] : 'Eternia' ?></span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="metismenu side-nav">

                    <li class="side-nav-item">
                        <a href="/admin/index.php" class="side-nav-link">
                            <i class="dripicons-direction"></i>
                            <span> 后台首页 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/set.php" class="side-nav-link">
                            <i class="dripicons-meter"></i>
                            <span class="badge badge-success float-right"><i class="dripicons-gear margin_0"></i></span>
                            <span> 基本设置 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/leavset.php" class="side-nav-link">
                            <i class="dripicons-view-apps"></i>
                            <span> 留言管理
                                <span class="badge badge-danger float-right"><?php echo $shu ?></span>
                            </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/littleset.php" class="side-nav-link">
                            <i class="dripicons-copy"></i>
                            <span> 点点滴滴
                                <span class="badge badge-danger float-right"><?php echo $diannub ?></span>
                            </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/loveimgset.php" class="side-nav-link">
                            <i class="dripicons-photo-group"></i>
                            <span> 恋爱相册
                                <span class="badge badge-danger float-right"><?php echo $imgnub ?></span>
                            </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/lovelist.php" class="side-nav-link">
                            <i class="dripicons-location"></i>
                            <span> 恋爱清单
                                <span class="badge badge-danger float-right"><?php echo $listnub ?></span>
                            </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/aboutset.php" class="side-nav-link">
                            <i class="dripicons-tags"></i>
                            <span> 关于页面</span>
                            <span class="menu-arrow"></span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/iplist.php" class="side-nav-link">
                            <i class="dripicons-location"></i>
                            <span> IP/拉黑</span>
                            <span class="menu-arrow"></span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/feifa.php" class="side-nav-link">
                            <i class="dripicons-time-reverse"></i>
                            <span> 非法访问</span>
                            <span class="menu-arrow"></span>
                        </a>
                    </li>
                </ul>

                <!-- Help Box 已删除 -->
                <!-- End Sidebar -->

                <div class="clearfix"></div>
                <!-- Sidebar -left -->
            </div>
            <!--侧边导航栏 End-->
            <!--内容页面 Start-->
            <div class="content-page">
                <!--内容 Start-->
                <div class="content">

                    <style>
                        .textHide {
                            width: 300px;
                            white-space: normal;
                        }

                        .textHide.index {
                            overflow: hidden;
                            text-overflow: ellipsis;
                            display: -webkit-box;
                            -webkit-box-orient: vertical;
                            -webkit-line-clamp: 2;
                        }


                        .toast-title {
                            font-size: 1.1rem;
                            margin-bottom: .3rem;
                        }

                        .color {
                            color: #999;
                        }

                        .form-control:focus {
                            border-color: #ff5295;
                        }

                        .form-control {
                            color: #b6b6b6;
                        }

                        .side-nav .side-nav-link i {
                            height: 20px;
                        }

                        span.badge.badge-danger-lighten {
                            font-size: 0.9rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }

                        span.badge.badge-primary-lighten {
                            font-size: 0.9rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }

                        ul.text {
                            line-height: 2rem;
                            font-size: 0.9rem;
                        }

                        ul {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }

                        h3.mt-0,
                        h5 {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 700;
                        }

                        .hhtext {
                            white-space: normal !important;
                        }

                        .notification-list .dropdown-menu.dropdown-menu-right {
                            border-radius: 0px 0px 10px 10px;
                            box-shadow: 0 8px 12px #efefef;
                        }

                        button.btn.btn-success.mb-2.mr-2 {
                            border-radius: 10px;
                        }

                        .badge-success-lighten,
                        .badge-warning-lighten{
                            font-size: 0.9rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                            padding: 0.4rem;
                        }

                        i {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }

                        .form-group.col-sm-4 {
                            margin-left: 0;
                            padding-left: 0;
                        }

                        #spotlight .header div {
                            padding-right: 40px;
                        }

                        div#test-editor {
                            z-index: 10;
                        }

                        .editormd-preview video {
                            width: 100%;
                        }

                        .table td,
                        .table th {
                            padding: 0.95rem;
                            vertical-align: middle;
                            border-top: 1px solid #e3eaef;
                        }

                        #img_url {
                            display: block;
                        }

                        .margin_0 {
                            margin: 0 !important;
                        }

                        .account-user-name {
                            text-transform: capitalize;
                        }

                        .leftbar-user .leftbar-user-name {
                            margin-left: 0px;
                        }

                        span.topnav-logo-lg,
                        span.topnav-logo-sm {
                            color: #fff;
                            font-size: 1.2rem;
                            text-transform: capitalize;
                        }

                        .card {
                            border-radius: 0.80rem;
                        }

                        .content-page {
                            padding-top: 20px;
                        }

                        h3.my-2.py-1 {
                            font-size: 2rem;
                        }

                        h3.my-2.py-1 i {
                            font-style: normal;
                            font-weight: 200;
                            font-size: 1.2rem;
                            margin-left: 0.5rem;
                        }

                        .row.footer_center {
                            width: 100%;
                            display: flex;
                            justify-content: center;
                            text-align: center;
                        }

                        .topnav-navbar {
                            padding: 0px 30px;
                            margin: 0;
                            min-height: 70px;
                        }

                        button.btn.btn-danger.mb-2.mr-2 {
                            border-radius: 0.4rem;
                        }

                        .table .action-icon:hover {
                            color: #ee7c74;
                        }

                        .form-group.mb-3.text_right {
                            text-align: right;
                        }

                        i.mdi.mdi-account-circle.mr-1.rihjt-0 {
                            margin-right: 0rem !important;
                        }

                        button.btn.btn-primary {
                            border-radius: 0.4rem;
                        }

                        h4.header-title.mb-3 {
                            text-align: center;
                            font-size: 1.2rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 700;
                        }

                        label {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }

                        h4.header-title.mb-3.size_18 {
                            font-size: 1.8rem;
                        }

                        table.dataTable tbody td.focus,
                        table.dataTable tbody th.focus {
                            outline: none !important;
                            outline-offset: 0;
                            background-color: white;
                        }

                        .text-muted {
                            white-space: nowrap;
                        }

                        .right_10 {
                            margin-left: 10px;
                            border-radius: 0.6rem;
                        }

                        iframe {
                            width: 100%;
                            height: 260px;
                            border-radius: 15px;
                            border: 2px solid #d9d9d9d1;
                            box-shadow: 2px 1px 15px rgb(36 37 38 / 44%);
                        }

                        quote {
                            padding: 5px 0px 5px 15px;
                            width: 100%;
                            display: block;
                            border-left: 3px solid #856f6f;
                            background: #f2f2f2;
                            border-radius: 0px 6px 6px 0px;
                            margin: 15px 0;
                        }

                        default-Code {
                            text-align: center;
                            width: 100%;
                            display: block;
                            color: #ff876c;
                        }

                        .editormd {
                            border-radius: 10px;
                            box-shadow: 0 4px 13px 0 rgb(115 118 120 / 14%);
                            height: 450px !important;
                            margin: 40px 0 !important;
                        }

                        .markdown-body h1 {
                            color: #383838 !important;
                            padding-bottom: 0.3em;
                            font-size: 2.25em;
                            line-height: 1.2;
                            border-bottom: 1px solid #eee;
                        }

                        .margin_left {
                            margin-left: 10px;
                        }

                        body {
                            cursor: url(../style/cur/cursor.cur), default;
                        }

                        a:hover {
                            cursor: url(../style/cur/hover.cur), pointer;
                        }

                        @media (min-width: 992px) {
                            .text-lg-right {
                                text-align: left !important;
                            }
                        }

                        @media (max-width: 767.98px) {
                            .navbar-custom {
                                padding: 0px;
                            }

                            .topnav-navbar .topnav-logo {
                                min-width: 50px;
                                display: none;
                            }
                        }

                        .navbar-custom.topnav-navbar {
                            position: fixed !important;
                            top: 0 !important;
                            left: 0 !important;
                            right: 0 !important;
                            z-index: 1001 !important;
                            background-color: #ffffff !important;
                            border-bottom: 1px solid #e3e6ef !important;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
                        }

                        .navbar-custom.topnav-navbar .topnav-logo,
                        .navbar-custom.topnav-navbar .topnav-logo-lg,
                        .navbar-custom.topnav-navbar .topnav-logo-sm {
                            color: #313a46 !important;
                        }

                        .navbar-custom.topnav-navbar .topbar-right-menu .nav-link {
                            color: #6c757d !important;
                        }

                        .navbar-custom.topnav-navbar .account-user-name {
                            color: #313a46 !important;
                        }

                        .navbar-custom.topnav-navbar .account-position {
                            color: #98a6ad !important;
                        }

                        .navbar-custom.topnav-navbar .nav-user {
                            background-color: transparent !important;
                            border: none !important;
                            border-left: none !important;
                            border-right: none !important;
                        }

                        .navbar-custom.topnav-navbar .nav-user:hover,
                        .navbar-custom.topnav-navbar .nav-user:focus {
                            background-color: #f1f3fa !important;
                        }

                        .navbar-custom.topnav-navbar .nav-user::before,
                        .navbar-custom.topnav-navbar .nav-user::after {
                            display: none !important;
                        }

                        .navbar-custom.topnav-navbar .topbar-right-menu > li {
                            border-left: none !important;
                        }

                        .dropdown-menu.dropdown-menu-animated.topbar-dropdown-menu.profile-dropdown {
                            background-color: #ffffff !important;
                            border: 1px solid #e3e6ef !important;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
                        }

                        .dropdown-menu.dropdown-menu-animated.topbar-dropdown-menu.profile-dropdown .dropdown-item.notify-item {
                            color: #313a46 !important;
                            background-color: transparent !important;
                        }

                        .dropdown-menu.dropdown-menu-animated.topbar-dropdown-menu.profile-dropdown .dropdown-item.notify-item:hover {
                            background-color: #f1f3fa !important;
                            color: #6658dd !important;
                        }

                        .dropdown-menu.dropdown-menu-animated.topbar-dropdown-menu.profile-dropdown .dropdown-item.notify-item i {
                            color: #98a6ad !important;
                        }

                        .dropdown-menu.dropdown-menu-animated.topbar-dropdown-menu.profile-dropdown .dropdown-item.notify-item:hover i {
                            color: #6658dd !important;
                        }

                        .wrapper {
                            margin-top: 70px !important;
                        }

                        .left-side-menu {
                            position: fixed !important;
                            top: 70px !important;
                            left: 0 !important;
                            bottom: 0 !important;
                            height: calc(100vh - 70px) !important;
                            width: 260px !important;
                            overflow-y: auto !important;
                            overflow-x: hidden !important;
                            z-index: 1000;
                        }

                        .left-side-menu .simplebar-content-wrapper,
                        .left-side-menu .simplebar-content {
                            height: 100% !important;
                        }

                        .side-nav {
                            margin-bottom: 0 !important;
                            padding-bottom: 20px !important;
                        }

                        .leftbar-user {
                            margin-bottom: 15px !important;
                            padding-top: 10px !important;
                        }

                        .content-page {
                            margin-left: 260px !important;
                        }
                    </style>

</body>

</html>