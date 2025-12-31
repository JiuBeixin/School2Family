<?php
// 定义你在微信后台填写的 Token
define("TOKEN", "S2F2025");

/**
 * 微信接入验证逻辑
 */
function checkSignature() {
    // 1. 获取微信加密签名的参数
    $signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
    $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
    $nonce     = isset($_GET["nonce"])     ? $_GET["nonce"]     : '';
    $echoStr   = isset($_GET["echostr"])   ? $_GET["echostr"]   : '';

    // 2. 将 token、timestamp、nonce 三个参数进行字典序排序
    $tmpArr = array(TOKEN, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);

    // 3. 将三个参数字符串拼接成一个字符串进行 sha1 加密
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    // 4. 开发者获得加密后的字符串可与 signature 对比
    if ($tmpStr == $signature) {
        // 验证通过，如果是验证请求，则返回 echostr 内容
        return $echoStr;
    } else {
        // 验证失败
        return false;
    }
}

// 执行验证
$result = checkSignature();

if ($result) {
    // 如果是第一次接入验证，微信会发送 echostr
    // 必须原样输出该字符串，接入才算成功
    echo $result;
} else {
    echo "Verification Failed";
}
?>