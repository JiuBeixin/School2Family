set http_proxy=http://127.0.0.1:7890
set https_proxy=http://127.0.0.1:7890



@echo off
set URL=https://school2family.vercel.app/api/index.php

:: 模拟真实数据的变量
set TO_USER=gh_f8c123456789
set FROM_USER=oUp6SjvY2b2AN96_1Z8_SAMPLE
set CONTENT=你好，我想咨询

echo Sending simulated user message to %URL%...

curl -X POST %URL% ^
-H "Content-Type: text/xml" ^
-d "<xml><ToUserName><![CDATA[%TO_USER%]]></ToUserName><FromUserName><![CDATA[%FROM_USER%]]></FromUserName><CreateTime>%date:~0,4%%date:~5,2%%date:~8,2%</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%CONTENT%]]></Content><MsgId>23948576123456789</MsgId></xml>"

echo.
echo Done! Check Vercel Logs for the XML response.
pause