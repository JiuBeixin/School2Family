<?php
define("TOKEN", "S2F2025");

// 1. 签名验证函数（必须保留，否则微信无法接入）
function checkSignature() {
    $signature = $_GET["signature"] ?? '';
    $timestamp = $_GET["timestamp"] ?? '';
    $nonce     = $_GET["nonce"]     ?? '';
    $tmpArr = array(TOKEN, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = sha1(implode($tmpArr));
    return $tmpStr == $signature;
}

if (!checkSignature()) {
    exit("error");
}

// 2. 处理 GET 请求（微信后台点击“提交”时使用）
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo $_GET["echostr"];
    exit;
}

// 3. 处理 POST 请求（粉丝发消息时）
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取微信推送的原始 XML 数据
    $postStr = file_get_contents("php://input");

    if (!empty($postStr)) {
        // 【关键】将收到的 XML 记录到 Vercel 的日志中
        error_log("收到微信消息推送: " . $postStr);
        
        // 也可以解析出具体的文字内容记录
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        error_log("用户 OpenID: " . $postObj->FromUserName);
        error_log("消息内容: " . $postObj->Content);
    }

    // 微信规定：如果你不回复消息，直接返回 success 或空字符串
    // 这样微信就不会提示“该公众号暂时无法提供服务”
    echo "success";
    exit;
}