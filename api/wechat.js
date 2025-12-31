const crypto = require('crypto');

// 微信服务器验证逻辑
const verifyWechatServer = (req, res) => {
    const token = 'S2F2025';
    const { signature, timestamp, nonce, echostr } = req.query;

    // 按字典序排序
    const tmpArr = [token, timestamp, nonce].sort();
    const tmpStr = tmpArr.join('');

    // SHA1 加密
    const hash = crypto.createHash('sha1');
    hash.update(tmpStr);
    const hashedStr = hash.digest('hex');

    // 验证 signature
    if (hashedStr === signature) {
        res.send(echostr); // 验证成功，返回 echostr
    } else {
        res.status(403).send('Forbidden'); // 验证失败
    }
};

// Express 路由
const express = require('express');
const app = express();

app.get('/api/wechat', verifyWechatServer);

const port = process.env.PORT || 3000;
app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});