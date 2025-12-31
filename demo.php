<?php
// 微信后端验证逻辑
define("TOKEN", "S2F2025");

function checkSignature() {
    // 获取微信服务器发送的参数
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    
    // 将 token、timestamp 和 nonce 按字典序排序
    $tmpArr = array(TOKEN, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    
    // 将排序后的数组拼接成字符串并进行 SHA1 加密
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);
    
    // 验证签名
    if ($tmpStr == $signature) {
        return true;
    } else {
        return false;
    }
}

// 主逻辑
if (isset($_GET["echostr"])) {
    // 验证签名
    if (checkSignature()) {
        // 返回 echostr 表示验证成功
        echo $_GET["echostr"];
    } else {
        // 验证失败
        echo "Signature verification failed.";
    }
} else {
    echo "No echostr parameter found.";
}
?>
