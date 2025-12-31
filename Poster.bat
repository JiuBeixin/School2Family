set http_proxy=http://127.0.0.1:7890
set https_proxy=http://127.0.0.1:7890



@echo off

set URL=https://school2family.vercel.app/api/index.php
echo Sending POST request to %URL%...

curl -X POST %URL% ^
-H "Content-Type: text/xml" ^
-d "<xml><ToUserName><![CDATA[gh_test]]></ToUserName><FromUserName><![CDATA[tester]]></FromUserName><CreateTime>123456</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[Hello Vercel]]></Content><MsgId>123456789</MsgId></xml>"

echo.
echo Request sent. Check your Vercel Logs.