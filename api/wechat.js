const crypto = require('crypto');

// 微信服务器验证逻辑
const verifyWechatServer = (req, res) => {
    try {
        const token = 'S2F2025';
        const { signature, timestamp, nonce, echostr } = req.query;

        // 检查参数是否存在
        if (!signature || !timestamp || !nonce || !echostr) {
            console.error('Missing parameters:', req.query);
            return res.status(400).send('Bad Request: Missing parameters');
        }

        // 按字典序排序
        const tmpArr = [token, timestamp, nonce].sort();
        const tmpStr = tmpArr.join('');

        // SHA1 加密
        const hash = crypto.createHash('sha1');
        hash.update(tmpStr);
        const hashedStr = hash.digest('hex');

        // 验证 signature
        if (hashedStr === signature) {
            console.log('Validation successful:', { signature, hashedStr });
            res.send(echostr); // 验证成功，返回 echostr
        } else {
            console.error('Validation failed:', { signature, hashedStr });
            res.status(403).send('Forbidden: Invalid signature'); // 验证失败
        }
    } catch (error) {
        console.error('Error occurred:', error);
        res.status(500).send('Internal Server Error');
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