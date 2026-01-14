# -*- coding: utf-8 -*-
from flask import Flask, request, make_response
import hashlib
import xml.etree.ElementTree as ET
import time

app = Flask(__name__)

# --- 配置区 ---
# 必须与你在微信公众平台后台填写的 Token 一致
TOKEN = "你自定义的Token" 

@app.route('/weixin', methods=['GET', 'POST'])
def wechat():
    # 1. 处理微信后台的 Token 验证 (GET 请求)
    if request.method == 'GET':
        data = request.args
        if len(data) == 0:
            return "Hello, this is WeChat server."
        
        signature = data.get('signature', '')
        timestamp = data.get('timestamp', '')
        nonce = data.get('nonce', '')
        echostr = data.get('echostr', '')

        # 字典序排序
        list_data = [TOKEN, timestamp, nonce]
        list_data.sort()
        sha1 = hashlib.sha1()
        sha1.update("".join(list_data).encode('utf-8'))
        hashcode = sha1.hexdigest()

        # 如果校验成功，返回 echostr
        if hashcode == signature:
            return echostr
        else:
            return ""

    # 2. 处理家长发送的消息 (POST 请求)
    if request.method == 'POST':
        xml_str = request.data
        if not xml_str:
            return ""
        
        # 解析 XML 数据
        xml_data = ET.fromstring(xml_str)
        msg_type = xml_data.find('MsgType').text
        from_user = xml_data.find('FromUserName').text
        to_user = xml_data.find('ToUserName').text

        if msg_type == 'text':
            content = xml_data.find('Content').text
            msg_id = xml_data.find('MsgId').text # 用于排重
            
            # 在服务器后台打印收到的消息，方便你查看
            print(f"\n[收到消息] 家长ID: {from_user}")
            print(f"[内容]: {content}")
            print(f"[消息ID]: {msg_id}")

            # 构造回复给家长的 XML (可选，如果不想回复，直接返回 "success")
            reply_xml = f"""
            <xml>
                <ToUserName><![CDATA[{from_user}]]></ToUserName>
                <FromUserName><![CDATA[{to_user}]]></FromUserName>
                <CreateTime>{int(time.time())}</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[老师已收到您的消息：{content}]]></Content>
            </xml>
            """
            return reply_xml

        return "success"

if __name__ == '__main__':
    # 微信必须使用 80 端口
    app.run(host='0.0.0.0', port=80)