<?php
header("Content-Type: application/json; charset=utf-8");

$qq = isset($_POST['qq']) ? trim($_POST['qq']) : (isset($_GET['qq']) ? trim($_GET['qq']) : '');

if (empty($qq) || !preg_match("/^[1-9][0-9]{4,}$/", $qq)) {
    echo json_encode([
        'Status' => false,
        'message' => 'QQ号码格式错误'
    ]);
    exit();
}

// 尝试多个API源获取QQ昵称
$nickname = '';
// QQ头像API（标准格式）
$avatar = "https://q1.qlogo.cn/g?b=qq&nk=" . $qq . "&s=100";

// 方法0: 使用 uapis.cn API（最可靠，优先使用）
$ch0 = curl_init();
curl_setopt($ch0, CURLOPT_URL, "https://uapis.cn/api/v1/social/qq/userinfo?qq=" . $qq);
curl_setopt($ch0, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch0, CURLOPT_TIMEOUT, 5);
curl_setopt($ch0, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch0, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch0, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch0, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36");
$result0 = curl_exec($ch0);
$curlError0 = curl_error($ch0);
$httpCode0 = curl_getinfo($ch0, CURLINFO_HTTP_CODE);
curl_close($ch0);

if (empty($curlError0) && $httpCode0 == 200 && $result0) {
    $data0 = json_decode($result0, true);
    if ($data0 !== null && is_array($data0) && isset($data0['nickname'])) {
        $potentialNick = trim($data0['nickname']);
        // 验证昵称有效性
        if (!empty($potentialNick) &&
            !preg_match('/^https?:\/\//', $potentialNick) &&
            mb_strlen($potentialNick, 'UTF-8') > 0 &&
            mb_strlen($potentialNick, 'UTF-8') < 50) {
            $nickname = $potentialNick;
            // 如果API返回了头像URL，也使用它（质量可能更好）
            if (isset($data0['avatar_url']) && !empty($data0['avatar_url'])) {
                $avatar = $data0['avatar_url'];
            }
        }
    }
}

// 方法2: 如果方法0失败，尝试腾讯官方QQ空间API（备用，至少头像能获取到）
if (empty($nickname)) {
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, "https://users.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?uins=" . $qq);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch2, CURLOPT_REFERER, "https://qzone.qq.com");
    curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36");
    $result2 = curl_exec($ch2);
    $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);

    if ($httpCode2 == 200 && $result2) {
        // QZone API返回格式: portraitCallBack({"1226530985":["头像URL",5019,-1,0,0,0,"昵称",0]})
        // 昵称在数组的第7个位置（索引6）
        if (preg_match('/portraitCallBack\((.+)\)/', $result2, $matches)) {
            $json = json_decode($matches[1], true);
            if (isset($json[$qq]) && is_array($json[$qq]) && count($json[$qq]) > 6) {
                // 昵称在索引6的位置
                $potentialNick = isset($json[$qq][6]) ? trim($json[$qq][6]) : '';
                // 验证不是URL（URL通常包含 http:// 或 https://）
                // 验证不是空字符串，不是纯数字，不是特殊字符
                if (!empty($potentialNick) &&
                    !preg_match('/^https?:\/\//', $potentialNick) &&
                    !preg_match('/^[\d\s\-]+$/', $potentialNick) &&
                    mb_strlen($potentialNick, 'UTF-8') > 0) {
                    // 尝试处理编码问题
                    $potentialNick = mb_convert_encoding($potentialNick, 'UTF-8', 'GBK');
                    $nickname = $potentialNick;
                }
            }
        }
    }
}

// 返回结果
if (!empty($nickname)) {
    echo json_encode([
        'Status' => true,
        'data' => [
            'nick' => $nickname,
            'avatar' => $avatar,
            'qq' => $qq
        ],
        'message' => '获取成功'
    ]);
} else {
    // 即使昵称获取失败，也返回头像URL
    echo json_encode([
        'Status' => false,
        'data' => [
            'nick' => '',
            'avatar' => $avatar,
            'qq' => $qq
        ],
        'message' => '昵称获取失败，请手动填写。头像已自动获取。'
    ]);
}

