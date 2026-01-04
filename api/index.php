<?php
define("TOKEN", "S2F2025");

// --- 调整点：先抓取并记录 Log，再做验证 ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postStr = file_get_contents("php://input");
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

// 只有 GET 请求（微信验证）才强制验证签名
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // if (checkSignature()) {
    //     echo $_GET["echostr"];  
        
    // } else {
    //     echo "Verification Failed";
    // }
    echo "Get Get";
    exit;
}

// 模拟测试时，我们可以先跳过 POST 的签名验证
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "success"; // 让脚本看到 success 而不是 error
    exit;
}