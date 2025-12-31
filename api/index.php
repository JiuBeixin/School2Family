<?php
define("TOKEN", "S2F2025");

// 1. 依然保留签名验证
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

// 2. 如果是 GET 请求，说明是微信在做接入验证
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo $_GET["echostr"];
    exit;
}

// 3. 如果是 POST 请求，说明微信在推送粉丝消息
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取微信推送的原始 XML 数据
    $postStr = file_get_contents("php://input");

    if (!empty($postStr)) {
        // 解析 XML
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName; // 粉丝的 OpenID
        $toUsername   = $postObj->ToUserName;   // 你的公众号原始 ID
        $keyword      = trim($postObj->Content); // 用户发送的内容
        $time         = time();

        // 准备一个简单的文本回复模板
        $textTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[text]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                    </xml>";

        // 自动回复：你发什么，我回什么（鹦鹉学舌）
        $contentStr = "你刚才说的是：" . $keyword;
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
        
        echo $resultStr;
    }
    exit;
}