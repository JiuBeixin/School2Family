import requests
import uuid
from datetime import datetime

# 定义目标 URL
url = "https://school2family.vercel.app/api/wechat"

# 生成唯一标识符
unique_id = str(uuid.uuid4())  # 使用 UUID
timestamp = datetime.now().isoformat()  # 使用 ISO 格式的时间戳

# 定义请求数据
data = {
    "id": unique_id,  # 唯一标识符
    "timestamp": timestamp,  # 时间戳
    "message": "Hello from Python"
}

# 发送 POST 请求
response = requests.post(url, json=data)

# 打印响应
print("Status Code:", response.status_code)
print("Response Text:", response.text)
print("Unique ID:", unique_id)
print("Timestamp:", timestamp)