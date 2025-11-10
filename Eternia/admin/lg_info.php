<!-- 推销内容已删除 -->

<?php if ($login['user'] == $adminuser): ?>
    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Warning - </strong> 当前账号为默认账号 请尽快修改！
    </div>
<?php endif; ?>

<?php if (password_verify($adminpw, $login['pw'])): ?>
    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Warning - </strong> 当前密码为默认密码 请尽快修改！
    </div>
<?php endif; ?>