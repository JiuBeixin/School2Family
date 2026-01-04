<?php
define("TOKEN", "S2F2025");

// 1. 获取并记录 POST 原始数据（方便调试）
$postStr = file_get_contents("php://input");
if (!empty($postStr)) {
    error_log("【收到 POST 原始数据】: " . $postStr);
}

function checkSignature() {
    $signature = $_GET["signature"] ?? '';
    $timestamp = $_GET["timestamp"] ?? '';
    $nonce     = $_GET["nonce"]     ?? '';
    $tmpArr = array(TOKEN, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = sha1(implode($tmpArr));
    return $tmpStr == $signature;
}

// 2. 处理微信验证 (GET 请求)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (checkSignature()) {
        echo $_GET["echostr"];
    } else {
        echo "Verification Failed";
    }
    exit;
}

// 3. 处理微信消息推送并被动回复 (POST 请求)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($postStr)) {
        // 解析微信推送到你服务器的 XML
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName; // 用户的 OpenID
        $toUsername   = $postObj->ToUserName;   // 公众号原始 ID
        $keyword      = trim($postObj->Content); // 用户发送的文本内容
        $time         = time();

        // 按照微信要求的格式定义回复模板
        // 注意：ToUserName 和 FromUserName 必须对调位置
        $textTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[text]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                    </xml>";

        if (!empty($keyword)) {
            // 构建回复内容
            $contentStr = "收到消息：" . $keyword;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
            echo $resultStr; // 输出 XML，微信会自动推送给用户
        } else {
            echo "success"; // 如果消息内容为空，回复 success
        }
    } else {
        echo "success";
    }
    exit;
}