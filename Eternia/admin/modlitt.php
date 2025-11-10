<?php
session_start();
include_once 'nav.php';
$id = $_GET['id'];
if (is_numeric($id)) {
    $stmt = $connect->prepare("SELECT * FROM article WHERE id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resarticle = $stmt->get_result();
$mod = mysqli_fetch_array($resarticle);
        $stmt->close();
    } else {
        die("数据库查询失败");
    }
} else {
    die("参数错误");
}
?>

<link href="/admin/editormd/css/editormd.css" rel="stylesheet">
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改文章—— <?php echo $mod['articletitle'] ?></h4>

                <form class="needs-validation" action="littleupda.php" method="post" onsubmit="return check()"
                      novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">标题</label>
                        <input name="articletitle" type="text" class="form-control" id="validationCustom01"
                               placeholder="请输入标题" value="<?php echo $mod['articletitle'] ?>" required>
                    </div>
                    <div id="test-editor">
                        <textarea name="articletext"><?php echo $mod['articletext'] ?></textarea>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <button class="btn btn-primary" type="button" id="littleupda">修改发布</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/admin/editormd/editormd.js"></script>
<script type="text/javascript">
    $(function () {
        var editor = editormd("test-editor", {
            htmlDecode: true,
            path: "/admin/editormd/lib/"

        });
    });
</script>

<?php
include_once 'footer.php';
?>

</body>
</html>